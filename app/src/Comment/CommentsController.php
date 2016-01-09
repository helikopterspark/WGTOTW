<?php

namespace CR\Comment;

/**
* A controller for users and admin related events.
*/
class CommentsController implements \Anax\DI\IInjectionAware {

	use \Anax\DI\TInjectable;

	/**
	 * Initialize the controller.
	 *
	 * @return void
	 */
	public function initialize() {
		$this->comments = new \CR\Comment\Comment();
		$this->comments->setDI($this->di);
	}

	/**
	 * Index page
	 *
	 */
	public function indexAction() {
		$this->theme->setTitle("Kommentarer");
		$this->views->add('comment/index', [], 'main-extended');
		$this->dispatcher->forward([
     		'controller' => 'comments',
     		'action'     => 'viewComments',
     		'params'	=> ['comments-', 'comments-'],
     	]);
	}

	/**
     * View all comments for a page.
     *
     * @param $id integer with question/answer id, $type string with type (question or answer), $pageId int for redirect to question page
     *
     * @return void
     */
	public function viewCommentsAction($id, $type, $pageId) {
		if (null == ($this->session->get('sorting'))) {
			$this->session->set('sorting', 'ASC');
			$change_sorting = 'DESC';
		}

		$set_sorting = $this->request->getGet('sorting');
		switch ($set_sorting) {
					case 'DESC':
						$change_sorting = 'ASC';
						$this->session->set('sorting', 'DESC');
						break;
					case 'ASC':
						$change_sorting = 'DESC';
						$this->session->set('sorting', 'ASC');
						break;
					default:
						$change_sorting = $this->session->get('sorting') === 'ASC' ? 'DESC' : 'ASC';
						break;
				}

		$sorting = 'created ' . $this->session->get('sorting');

		$all = $this->comments->query("c.*")
			->from('comment AS c')
			->where("c.deleted IS NULL")
			->join("comment2".$type." AS c2x", "c.id = c2x.idComment")
			->andWhere("c2x.id".ucfirst($type)." = ?")
			->groupBy("c.id")
			->orderBy("c.".$sorting)
			->execute([$id]);

		$this->views->add('comment/comment-container-top', [], 'main-extended');
		$noForm = false;
		// If $all array not empty, get info and list comments
		if (is_array($all)) {
			// convert comment content from markdown to html, and get user object and Gravatar
			foreach ($all as $xid => &$comment) {
				$comment->filteredcontent = $this->textFilter->doFilter($comment->getProperties()['content'], 'shortcode, markdown');
				$users = new \CR\Users\User();
				$users->setDI($this->di);
				$comment->user = $users->find($comment->getProperties()['commentUserId']);
				$comment->user->gravatar = 'http://www.gravatar.com/avatar/' . md5(strtolower(trim($comment->user->getProperties()['email']))) . '.jpg?s=50&d=identicon';
			}

			if (count($all) > 0) {

				$this->views->add('comment/header', [
					'comments' => $all,
					'sorting' => $change_sorting,
				], 'main-extended');

				foreach ($all as $comment_post) {
					// Get form view if add/edit is clicked
					$noForm = false;
					if ($this->request->getGet('editcomment') && $comment_post->getProperties()['id'] == $this->request->getGet('commentid')) {
						if (!$this->di->UserloginController->checkLoginCorrectUser($comment_post->getProperties()['commentUserId'])) {
							// Not logged in
							$this->di->UserloginController->redirectToLogin('Endast '.$comment_post->user->getProperties()['acronym'].' kan redigera kommentaren');
						}
						$noForm = true;
						$this->edit(array('comment' => $comment_post, 'type' => $type, 'pageId' => $pageId));
					} else {
						$vote = $this->vote->checkVote($comment_post, 'comment');
						$this->views->add('comment/view', [
							'comment' => $comment_post,
							'vote' => $vote,
							'qid' => $pageId,
						], 'main-extended');
					}
				}
			}
		}
		// Insert form for new comment if button is clicked and user is logged in
		if ($this->request->getGet($type.'comment') && $id == $this->request->getGet('postid')) {
			if ($this->di->UserloginController->checkLoginSimple()) {
				$noForm = true;
				$this->add(array('postId' => $id, 'type' => $type, 'pageId' => $pageId));
			} else {
				// Not logged in
				$this->di->UserloginController->redirectToLogin('Logga in för att kommentera');
			}

		} else {

			$this->views->add('comment/bottom', [
				'noForm' => $noForm,
				'type' => $type,
				'postId' => $id,
			], 'main-extended');
		}

		$this->views->add('comment/comment-container-bottom', [], 'main-extended');
	}

	/**
	 * Add comment
	 *
	 * @param array $param with page and redirect
	 *
	 * @return void
	 */
	private function add($params) {

		$form = new \CR\HTMLForm\CFormAddComment($params);
		$form->setDI($this->di);
		$form->check();

		$noForm = true;

		$this->views->add('comment/comment-form-container', [
			'content'	=> $form->getHTML(),
			'postId' => $params['postId'],
			'type'	=> $params['type'],
			'pageId'	=> $params['pageId'],
			'noForm'	=> $noForm,
		], 'main-extended');
	}

	/**
	 * Edit comment
	 *
	 * @param int $id of comment, array $param with parameters
	 *
	 * @return void
	 */
	private function edit($params) {

		//$comment = $this->comments->find($params['commentId']);
		$form = new \CR\HTMLForm\CFormEditComment($params);
		$form->setDI($this->di);
		$form->check();

		$comment = $params['comment'];
		$noForm = true;

		$this->views->add('comment/comment-editform-container', [
			'content'	=> $form->getHTML(),
			'pageId' => $params['pageId'],
			'comment' => $comment,
			'noForm'	=> $noForm,
		], 'main-extended');
	}

	/**
	* Upvote action
	*
	* @param string $id, comment ID
	*
	* @return void
	*/
	public function upvoteAction($id) {

		$res = $this->comments->find($id);
		$q = $this->request->getGet('qid');
		$this->vote->castVote($res, 'comment', 'upvotes', $q);
	}

	/**
	* Downvote action
	*
	* @param string $id, comment ID
	*
	* @return void
	*/
	public function downvoteAction($id) {

		$res = $this->comments->find($id);
		$q = $this->request->getGet('qid');
		$this->vote->castVote($res, 'comment', 'downvotes', $q);
	}

	/**
	 * Delete all comments for a specified page
	 *
	 * @param string $page with name of page
	 *
	 * @return void
	 */
	public function deletePageCommentsAction($page = null) {
		/*
		$condition = "page = '{$page}'";

        $this->db->delete(
            $this->comments->getSource(),
            $condition
        );
        $this->db->execute();

        $this->indexAction();
        $this->views->add('theme/index', [
        	'content' => '<h2>Resultat</h2><p>Kommentarer för sidan \'' . $page . '\' raderades.</p>'], 'sidebar-reduced');
			*/
    }

	/**
	 * Delete all comments
	 *
	 * @param
	 *
	 * @return void
	 */
	public function deleteAllAction() {
		/*
        $this->db->delete(
            $this->comments->getSource()
        );
        $this->db->execute();

		$this->indexAction();
        $this->views->add('theme/index', [
        	'content' => '<h2>Resultat</h2><p>Alla kommentarer raderades.</p>'], 'sidebar-reduced');
			*/
	}

	/**
	 * Setup database
	 *
	 * @return void
	 */
	public function setupAction() {
		//$this->db->setVerbose();
		/*
		$this->db->dropTableIfExists('comment')->execute();

		$this->db->createTable(
			'comment',
			[
			'id' => ['integer', 'primary key', 'not null', 'auto_increment'],
			'content' => ['text'],
			'name' => ['varchar(80)'],
			'email' => ['varchar(80)'],
			'url' => ['varchar(80)'],
			'ip' => ['varchar(20)'],
			'created' => ['datetime'],
			'updated' => ['datetime'],
			'deleted' => ['datetime'],
			'redirect' => ['varchar(20)'],
			'page' => ['varchar(20)'],
			]
			)->execute();


		$minutes_to_add = 360;
		$time = new \DateTime();

		$lorem = $this->fileContent->get('lorem.md');

		$this->db->insert(
			'comment',
			['content', 'name', 'email', 'url', 'ip', 'created', 'updated', 'redirect', 'page']
			);
		$interval = new \DateInterval('PT' . $minutes_to_add . 'M');
		$interval->invert = 1;
		$time->add($interval);
		$stamp = $time->format('Y-m-d H:i:s');

		$this->db->execute([
			$lorem,
			'Carl',
			'esp_horizon@hotmail.com',
			'http://dbwebb.se',
			'111.0.0.1',
			$stamp,
			null,
			'',
			'start'
			]);

		$interval = new \DateInterval('PT' . $minutes_to_add . 'M');
		$interval->invert = 1;
		$time->add($interval);
		$stamp = $time->format('Y-m-d H:i:s');

		$this->db->execute([
			$lorem . $lorem,
			'Nisse Hulth',
			'nisse.hulth@mail.com',
			'http://dbwebb.se',
			'111.0.0.1',
			$stamp,
			null,
			'',
			'redovisning'
			]);

		$interval = new \DateInterval('PT' . 2 . 'M');
		$interval->invert = 1;
		$time->add($interval);
		$stamp = $time->format('Y-m-d H:i:s');

		$this->db->execute([
			$lorem,
			'Carl',
			'esp_horizon@hotmail.com',
			'http://dbwebb.se',
			'111.0.0.1',
			$stamp,
			null,
			'',
			'redovisning'
			]);

		$interval = new \DateInterval('PT' . $minutes_to_add . 'M');
		$interval->invert = 1;
		$time->add($interval);
		$stamp = $time->format('Y-m-d H:i:s');

		$this->db->execute([
			'Grym sida.',
			'Mikael',
			'mikael.roos@bth.se',
			'http://dbwebb.se',
			'111.0.0.1',
			$stamp,
			null,
			'',
			'comments-'
			]);

		$interval = new \DateInterval('PT' . $minutes_to_add . 'M');
		$interval->invert = 1;
		$time->add($interval);
		$stamp = $time->format('Y-m-d H:i:s');

		$this->db->execute([
			$lorem,
			'Carl',
			'esp_horizon@hotmail.com',
			'http://dbwebb.se',
			'111.0.0.1',
			$stamp,
			null,
			'',
			'comments-'
			]);

		$interval = new \DateInterval('PT' . $minutes_to_add . 'M');
		$interval->invert = 1;
		$time->add($interval);
		$stamp2 = $time->format('Y-m-d H:i:s');

		$this->db->execute([
			$lorem . $lorem,
			'Nisse Hulth',
			'nisse.hulth@mail.com',
			'http://dbwebb.se',
			'111.0.0.1',
			$stamp2,
			null,
			'',
			'comments-'
			]);

		$interval = new \DateInterval('PT' . $minutes_to_add . 'M');
		$interval->invert = 1;
		$time->add($interval);
		$stamp = $time->format('Y-m-d H:i:s');

		$this->db->execute([
			$lorem.$lorem.$lorem,
			'Carl',
			'esp_horizon@hotmail.com',
			'http://dbwebb.se',
			'111.0.0.1',
			$stamp,
			$stamp2,
			'',
			'comments-'
			]);

		$this->indexAction();
        $this->views->add('theme/index', [
        	'content' => '<h2>Resultat</h2><p>Databasen återställdes.</p>'], 'sidebar-reduced');
			*/
	}
}
