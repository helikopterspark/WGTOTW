<?php

namespace CR\Users;

/**
* A controller for users and admin related events.
*/
class UsersController implements \Anax\DI\IInjectionAware {

	use \Anax\DI\TInjectable;

	/**
	 * Initialize the controller.
	 *
	 * @return void
	 */
	public function initialize() {
		$this->users = new \CR\Users\User();
		$this->users->setDI($this->di);
	}

	/**
	 * List all users.
	 *
	 * @return void
	 */
	public function indexAction() {

		$all = $this->users->findAll();

		if (!$this->di->UserloginController->checkLoginAdmin()) {
			// remove admin users from the array
			$count = 0;
			foreach ($all as $user) {
				if ($user->getProperties()['isAdmin'] !== null) {
					unset($all[$count]);
				}
				$count++;
			}
		}

		foreach ($all as $user) {
			$user->gravatar = 'http://www.gravatar.com/avatar/' . md5(strtolower(trim($user->getProperties()['email']))) . '.jpg?s=60&d=identicon';
			$user->stats = $this->getUserStats($user->getProperties()['id']);
		}

		$this->theme->setTitle("Alla användare");
		$this->views->add('users/list-all', [
			'users' => $all,
			'title' => "Alla användare",
		], 'fullpage');
	}

	/**
	 * List all users.
	 *
	 * @return void
	 */
	public function indexadminAction() {

		$all = $this->users->findAll();

		foreach ($all as $user) {
			$user->gravatar = 'http://www.gravatar.com/avatar/' . md5(strtolower(trim($user->getProperties()['email']))) . '.jpg?s=20&d=identicon';
			$user->stats = $this->getUserStats($user->getProperties()['id']);
		}

		$this->theme->setTitle("Alla användare");
		$this->views->add('users/list-all-admin', [
			'users' => $all,
			'title' => "Alla användare",
			], 'main');
		$this->views->add('users/users-sidebar', [], 'sidebar');
		$this->addLegend();
	}

	/**
	* Add legend as triptych
	*
	* @return
	*/
	private function addLegend() {
		$legend1 = $this->fileContent->get('users/users-legend.html');
		$legend2 = $this->fileContent->get('users/users-legend-2.html');
		$legend3 = $this->fileContent->get('users/users-legend-3.html');

		$this->theme->addClassAttributeFor('wrap-triptych', 'smaller-text');

		$this->views->add('theme/region-small', ['content' => $legend1], 'triptych-1');
		$this->views->add('theme/region-small', ['content' => $legend2], 'triptych-2');
		$this->views->add('theme/region-small', ['content' => $legend3], 'triptych-3');
	}

	/**
	 * List user with id.
	 *
	 * @param int $id of user to display
	 *
	 * @return void
	 */
	public function idAction($id = null) {
		// Get user
		$user = $this->users->find($id);

		if ($user) {
			$user->gravatar = 'http://www.gravatar.com/avatar/' . md5(strtolower(trim($user->getProperties()['email']))) . '.jpg?s=130&d=identicon';
			// Get user answers
			$uAnswers = $this->getUserAnswers($id);
			// Get user comments
			$uComments = $this->getUserComments($id);
			// Get user questions
			$uQuestions = $this->getUserQuestions($id);
			$uQuestions = $this->di->QuestionController->getRelatedData($uQuestions);

			$rank = $this->getUserStats($id);
			$votes = $this->vote->getTotalUserVotes($id);
			// Check Activity tab
			$tab = $this->di->request->getGet('tab') ? $this->di->request->getGet('tab') : 'questions';
			switch ($tab) {
				case 'answers':
					$tabContent = $uAnswers;
					break;
				case 'comments':
					$tabContent = $uComments;
					break;
				default:
				// questions as default
					$tabContent = $uQuestions;
					break;
			}

			$this->theme->setTitle("Användare " . $user->acronym);
			$this->views->add('users/view', [
				'user' => $user,
				'rank' => $rank,
				'votes' => $votes,
				'title' => 'Användare ' . $user->acronym,
				'flash' => $this->di->flashmessage->outputMsgs(),
			], 'fullpage');

			$this->views->add('users/tab-'.$tab, [
				'qCount' => count($uQuestions),
				'aCount' => count($uAnswers),
				'cCount' => count($uComments['questioncomments']) + count($uComments['answercomments']),
				'user' => $user,
				'content' => $tabContent,
			], 'fullpage');
			//$this->views->add('users/users-sidebar', [], 'sidebar');
			$this->di->flashmessage->clearMessages();
		} else {
			$url = $this->url->create('users');
			$this->response->redirect($url);
		}
	}

	/**
	* Get user stats
	*
	* @param int $id, user ID
	*
	* @return int $stats, sum of activity and rank of posts made
	*/
	public function getUserStats($id) {

		// Get no of answers and sum of upvotes and downvotes for user
		$this->db->select("COUNT(*) AS noOfAnswers, COUNT(accepted) AS accAns, SUM(upvotes) AS aUpvotes, SUM(downvotes) AS aDownvotes")
			->from('answer')
			->where("answerUserId = ?")
			->andWhere("deleted IS NULL")
			->execute([$id]);
		$aStats = $this->db->fetchAll();
		$stats = $aStats[0]->noOfAnswers + $aStats[0]->accAns + $aStats[0]->aUpvotes - $aStats[0]->aDownvotes;

		// Get no of comments and sum of upvotes and downvotes for user
		$this->db->select("COUNT(*) AS noOfComments, SUM(upvotes) AS cUpvotes, SUM(downvotes) AS cDownvotes")
			->from('comment')
			->where("commentUserId = ?")
			->andWhere("deleted IS NULL")
			->execute([$id]);
		$cStats = $this->db->fetchAll();
		$stats += $cStats[0]->noOfComments + $cStats[0]->cUpvotes - $cStats[0]->cDownvotes;

		// Get no of questions and sum of upvotes and downvotes for user
		$this->db->select("COUNT(*) AS noOfQuestions, SUM(upvotes) AS qUpvotes, SUM(downvotes) AS qDownvotes")
			->from('question')
			->where("questionUserId = ?")
			->andWhere("deleted IS NULL")
			->execute([$id]);
		$qStats = $this->db->fetchAll();
		$stats += $qStats[0]->noOfQuestions + $qStats[0]->qUpvotes - $qStats[0]->qDownvotes;

		return $stats;
	}

	/**
	* Get user answers
	*
	* @param int $id, user ID
	*
	* @return array $answers, array with Answer objects plus question title
	*/
	private function getUserAnswers($id) {
		$userA = new \CR\Answer\Answer();
		$userA->setDI($this->di);
		$answers = $userA->query("a.*, q.title AS qtitle")
			->from('answer AS a')
			->join('question AS q', 'a.questionId = q.id')
			->where("a.answerUserId = ?")
			->andWhere("a.deleted IS NULL")
			->orderBy("a.upvotes - a.downvotes DESC")
			->execute([$id]);

		return $answers;
	}

	/**
	* Get user comments
	*
	* @param int $id, user ID
	*
	* @return array $comments, array with 2 arrays of Comment objects
	*/
	private function getUserComments($id) {
		$userC = new \CR\Comment\Comment();
		$userC->setDI($this->di);
		$comments['questioncomments'] = $userC->query("c.*, q.id AS qID, q.title AS qtitle")
			->from('comment AS c')
			->join('comment2question AS c2q', 'c.id = c2q.idComment')
			->join('question AS q', 'c2q.idQuestion = q.id')
			->where("c.commentUserId = ?")
			->andWhere("c.deleted IS NULL")
			->orderBy("c.upvotes - c.downvotes DESC")
			->execute([$id]);

		$comments['answercomments'] = $userC->query("c.*, a.questionID AS qID, a.id AS aID, a.content AS acontent")
			->from('comment AS c')
			->join('comment2answer AS c2a', 'c.id = c2a.idComment')
			->join('answer AS a', 'c2a.idAnswer = a.id')
			->where("c.commentUserId = ?")
			->andWhere("c.deleted IS NULL")
			->orderBy("c.upvotes - c.downvotes DESC")
			->execute([$id]);
		// Clean comment content from markdown and html-tags
		foreach ($comments['answercomments'] as $comment) {
			$md2html = $this->textFilter->doFilter($comment->getProperties()['acontent'], 'shortcode, markdown');
			$comment->filteredcontent = strip_tags($md2html);
		}

		return $comments;
	}

	/**
	* Get user questions
	*
	* @param int $id, user ID
	*
	* @return array $questions, array with Question objects
	*/
	private function getUserQuestions($id) {
		$userQ = new \CR\Question\Question();
		$userQ->setDI($this->di);
		$questions = $userQ->query()
			->where("questionUserId = ?")
			->andWhere("deleted IS NULL")
			->orderBy("upvotes - downvotes DESC")
			->execute([$id]);

		return $questions;
	}

	/**
	 * Add new user.
	 *
	 * @param string $acronym of user to add.
	 *
	 * @return void
	 */
	public function addAction($acronym = null) {

		$this->users = $this->di->session->get('tempuser');

		$form = new \CR\HTMLForm\CFormAddUser($this->users);
		$form->setDI($this->di);
		$form->check();

		$this->di->theme->setTitle("Registrera användare");
		$this->views->add('theme/index', [
			'title' => 'Registrera användare',
			'content' => '<h2>Registrera användare</h2>' . $this->di->flashmessage->outputMsgs() . $form->getHTML(),
			], 'main');
		//$this->views->add('users/users-sidebar', [], 'sidebar');
		$this->di->flashmessage->clearMessages();
	}

	/**
	 * Update user.
	 *
	 * @param int $id of user to add.
	 *
	 * @return void
	 */
	public function updateAction($id = null) {
		$content = null;
		if (!$this->di->UserloginController->checkLoginSimple()) {
			// Not logged in
			$this->di->UserloginController->redirectToLogin('Logga in för att redigera användare');

		} elseif ($this->di->UserloginController->checkLoginCorrectUser($id)) {
			// User is logged in, show update form
			$user = $this->users->find($id);
			$form = new \CR\HTMLForm\CFormEditUser($user);
			$form->setDI($this->di);
			$form->check();

			$content = $form->getHTML();

		} else {
			// Wrong user is logged in
			//$this->di->flashmessage->error('Fel användare är inloggad.');
			$content = '<p>Den gubben går inte! Fel användare är inloggad.</p>';

		}

		$this->di->theme->setTitle("Uppdatera användare");
		$this->views->add('theme/index', [
			'title' => 'Uppdatera användare',
			'content' => '<h2>Uppdatera användare</h2>' . $this->di->flashmessage->outputMsgs() . $content
		], 'main-extended');
		//$this->views->add('users/users-sidebar', [], 'sidebar');
		$this->di->flashmessage->clearMessages();
	}

	/**
	 * Delete user.
	 *
	 * @param integer $id of user to delete.
	 *
	 * @return void
	 */
	public function deleteAction($id = null) {
		if (!isset($id)) {
			die("Missing id");
		}

		$res = $this->users->delete($id);

		$url = $this->url->create('users');
		$this->response->redirect($url);
	}

	/**
	 * Delete (soft) user.
	 *
	 * @param integer $id of user to soft delete.
	 *
	 * @return void
	 */
	public function softDeleteAction($id = null) {
		if (!isset($id)) {
			die("Missing id");
		}

		$now = date('Y-m-d H:i:s');

		$user = $this->users->find($id);

		$user->deleted = $now;
		$user->active = null;
		$user->save();

		$url = $this->url->create('users/id/' . $id);
		$this->response->redirect($url);
	}

	/**
	 * Undo delete (soft) user.
	 *
	 * @param integer $id of user to undo soft delete.
	 *
	 * @return void
	 */
	public function undoSoftDeleteAction($id = null) {
		if (!isset($id)) {
			die("Missing id");
		}

		$now = date('Y-m-d H:i:s');

		$user = $this->users->find($id);

		$user->deleted = null;
		//$user->active = $now;
		$user->save();

		$url = $this->url->create('users/id/' . $id);
		$this->response->redirect($url);
	}

	/**
	 * List all active and not deleted users.
	 *
	 * @return void
	 */
	public function activeAction() {
		$all = $this->users->query()
		->where('active IS NOT NULL')
		->andWhere('deleted IS NULL')
		->execute();

		$this->theme->setTitle("Aktiva användare");
		$this->views->add('users/list-all', [
			'users' => $all,
			'title' => "Aktiva användare",
			], 'main');
		$this->views->add('users/users-sidebar', [], 'sidebar');
		$this->addLegend();
	}

	/**
	 * List all active and not deleted users.
	 *
	 * @return void
	 */
	public function inactiveAction() {
		$all = $this->users->query()
		->where('active IS NULL')
		->andWhere('deleted IS NULL')
		->execute();

		$this->theme->setTitle("Inaktiva användare");
		$this->views->add('users/list-all', [
			'users' => $all,
			'title' => "Inaktiva användare",
			], 'main');
		$this->views->add('users/users-sidebar', [], 'sidebar');
		$this->addLegend();
	}

	/**
	 * List all active and not deleted users.
	 *
	 * @return void
	 */
	public function nonDeletedAction() {
		$all = $this->users->query()
		->where('deleted IS NULL')
		->execute();

		$this->theme->setTitle("Aktiva och inaktiva användare");
		$this->views->add('users/list-all', [
			'users' => $all,
			'title' => "Aktiva och inaktiva användare",
			], 'main');
		$this->views->add('users/users-sidebar', [], 'sidebar');
		$this->addLegend();
	}

	/**
	 * List all trashed (softdeleted) users.
	 *
	 * @return void
	 */
	public function trashAction() {
		$all = $this->users->query()
		->where('deleted IS NOT NULL')
		->execute();

		$this->theme->setTitle("Papperskorgen");
		$this->views->add('users/list-all', [
			'users' => $all,
			'title' => "Papperskorgen",
			], 'main');
		$this->views->add('users/users-sidebar', [], 'sidebar');
		$this->addLegend();
	}

	/**
	 * Setup database
	 *
	 * @return void
	 */
	public function setupAction() {
		//$this->db->setVerbose();
		/*
		$this->db->dropTableIfExists('user')->execute();

		$this->db->createTable(
			'user',
			[
			'id' => ['integer', 'primary key', 'not null', 'auto_increment'],
			'acronym' => ['varchar(20)', 'unique', 'not null'],
			'email' => ['varchar(80)'],
			'name' => ['varchar(80)'],
			'password' => ['varchar(255)'],
			'created' => ['datetime'],
			'updated' => ['datetime'],
			'deleted' => ['datetime'],
			'active' => ['datetime'],
			]
			)->execute();
		*/
		$this->db->insert(
			'user',
			['acronym', 'email', 'name', 'password', 'created', 'updated', 'active', 'deleted']
			);

		$now = date('Y-m-d H:i:s');

		$enc_password = $this->encryptPassword('admin');
		$this->db->execute([
			'admin',
			'admin@dbwebb.se',
			'Administrator',
			$enc_password,
			$now,
			null,
			$now,
			null
			]);

		$enc_password = $this->encryptPassword('johndoe');
		$this->db->execute([
			'johndoe',
			'johndoe@dbwebb.se',
			'John Doe',
			$enc_password,
			$now,
			null,
			$now,
			null
			]);

		$enc_password = $this->encryptPassword('janedoe');
		$this->db->execute([
			'janedoe',
			'janedoe@dbwebb.se',
			'Jane Doe',
			$enc_password,
			$now,
			null,
			null,
			null
			]);

		$enc_password = $this->encryptPassword('nisse');
		$this->db->execute([
			'nisse',
			'nisse@dbwebb.se',
			'Nisse Hulth',
			$enc_password,
			$now,
			null,
			null,
			$now
			]);

		$url = $this->url->create('users');
		$this->response->redirect($url);
	}

	/**
	 * Encrypt password depending on PHP version
	 *
	 * @param string $password, password as string
	 *
	 * @return string $enc_password, encrypted password
	 */
	private function encryptPassword($password) {
		if (version_compare(phpversion(), '5.5.0', '<')) {
            $enc_password = md5($password);
        } else {
            $enc_password = password_hash($password, PASSWORD_DEFAULT);
        }

		return $enc_password;
	}
}
