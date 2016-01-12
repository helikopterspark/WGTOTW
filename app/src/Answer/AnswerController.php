<?php

namespace CR\Answer;

/**
* A controller for Answer and CRUD related events.
*/
class AnswerController implements \Anax\DI\IInjectionAware {

	use \Anax\DI\TInjectable;

	private $question;

	/**
	 * Initialize the controller.
	 *
	 * @return void
	 */
	public function initialize() {
		$this->answer = new \CR\Answer\Answer();
		$this->answer->setDI($this->di);
	}

	/**
	 * List all answers for questionId
	 *
	 * @param Question object $question
	 *
	 * @return void
	 */
	public function indexAction($question) {

		$this->question = $question;

		// Get sort order
		if (null == ($this->session->get('answersorting'))) {
			$this->session->set('answersorting', 'rank');
			$change_answer_sorting = 'datum';
		}

		$set_answer_sorting = $this->request->getGet('answersorting');

		switch ($set_answer_sorting) {
					case 'datum':
						$change_answer_sorting = 'rank';
						$this->session->set('answersorting', 'datum');
						break;
					case 'rank':
						$change_answer_sorting = 'datum';
						$this->session->set('answersorting', 'rank');
						break;
					default:
						$change_answer_sorting = $this->session->get('answersorting') === 'rank' ? 'datum' : 'rank';
						break;
				}

		$answer_sorting = $this->session->get('answersorting');

		if ($answer_sorting == 'rank') {
			$sorting = 'upvotes - downvotes DESC';
		} else {
			$sorting = 'created ASC';
		}

		// Get all answers to questionId
		$all = null;
		$all = $this->answer->query()
			->where("questionId = " . $this->question->getProperties()['id'])
			->groupBy("id")
			->orderBy($sorting)
			->execute();

		$all = $this->getRelatedData($all);

		$this->views->add('answer/header', [
			'content' => count($all),
			'title' => 'svar',
			'answersorting' => $change_answer_sorting,
		], 'main-extended');

		$answerform = false;

		// List all answers to question
		foreach ($all as $answer_post) {
			$editform = false;
			// Insert edit form at answer position, if edit is clicked and author is logged in
			if ($this->request->getGet('editanswer') && $this->request->getGet('answerid') === $answer_post->getProperties()['id']) {
				if (!$this->di->UserloginController->checkLoginCorrectUser($answer_post->getProperties()['answerUserId'])) {
					// Not logged in
					$this->di->UserloginController->redirectToLogin('Endast '.$answer_post->user->getProperties()['acronym'].' kan redigera svaret');
				}
				$editform = true;
				$answerform = false;
				$this->edit($answer_post);
			} else {
				$vote = $this->vote->checkVote($answer_post, 'answer');
			// Display answer
			$this->views->add('answer/index', [
				'content' => [$answer_post],
				'questionuserid' => $this->question->user->getProperties()['id'],
				'vote' => $vote,
			], 'main-extended');
		}
			// Get comments to answer
			$this->dispatcher->forward([
				'controller' => 'comments',
				'action'     => 'viewComments',
				'params'	=> [$answer_post->getProperties()['id'], 'answer', $this->question->getProperties()['id']],
			]);
		}

		// Insert form for new answer if button is clicked and user is logged in
		if ($this->request->getGet('newanswer')) {
			if ($this->di->UserloginController->checkLoginSimple()) {
				$answerform = true;
				$this->add($this->question->getProperties()['id']);
			} else {
				// Not logged in
				$this->di->UserloginController->redirectToLogin();
			}

		} else {

		// Bottom view
		$this->views->add('answer/bottom', [
			'questionId' => $this->question->getProperties()['id'],
			'answerform' => $answerform,
		], 'main-extended');
	}

	}

	/**
	 * Find with id.
	 *
	 * @param int $id
	 *
	 * @return void
	 */
	public function idAction($id = null) {

		$res = $this->answer->find($id);

		if ($res) {
			$this->theme->setTitle('Answer');
			$this->views->add('answer/view', [
				'content' => [$res],
				'title' => 'Answer Detail view',
				], 'main');
		} else {
			$url = $this->url->create('answer');
			$this->response->redirect($url);
		}
	}

	/**
	 * Add answer to Question
	 *
	 * @param int @questionId
	 *
	 * @return void
	 */
	private function add($questionId = null) {

		$form = new \CR\HTMLForm\CFormAddAnswer($questionId);
		$form->setDI($this->di);
		$form->check();

		$answerform = true;

		$this->views->add('answer/bottom', [
			'answerform' => $answerform,
			'questionId' => $questionId,
			'content' => $form->getHTML()
		], 'main-extended');
	}

	/**
	 * Add answer to Question
	 *
	 * @param int @questionId
	 *
	 * @return void
	 */
	private function edit($answer = null) {

		$form = new \CR\HTMLForm\CFormEditAnswer($answer);
		$form->setDI($this->di);
		$form->check();

		$answerform = true;

		$this->views->add('answer/answer-editform-container', [
			'answer' => $answer,
			'content' => $form->getHTML()
		], 'main-extended');
	}

	/**
	 * Delete
	 *
	 * @param integer $id
	 *
	 * @return void
	 */
	public function deleteAction($id = null) {
		if (!isset($id)) {
			die('Missing id');
		}

		//$res = $this->answer->delete($id);
	}

	/**
	* Get user data
	*
	* @param array $data questions fetched from db
	*
	* @return array $data questions with user data, answers and comments
	*/
	public function getRelatedData($data) {
		// If $data array not empty, convert question content from markdown to html, and get user data, Gravatars and tags
		if (is_array($data)) {
			foreach ($data as $id => &$answer) {
				$answer->filteredcontent = $this->textFilter->doFilter($answer->getProperties()['content'], 'shortcode, markdown');
				$users = new \CR\Users\User();
				$users->setDI($this->di);
				$answer->user = $users->find($answer->getProperties()['answerUserId']);
				$answer->user->stats = $this->UsersController->getUserStats($answer->getProperties()['answerUserId']);
				$answer->user->gravatar = 'http://www.gravatar.com/avatar/' . md5(strtolower(trim($answer->user->getProperties()['email']))) . '.jpg?d=identicon';

			}
		}
		return $data;
	}


	/**
	* Upvote action
	*
	* @param string $id, answer ID
	*
	* @return void
	*/
	public function upvoteAction($id) {

		$res = $this->answer->find($id);
		$this->vote->castVote($res, 'answer', 'upvotes', $res->getProperties()['questionId']);
	}

	/**
	* Downvote action
	*
	* @param string $id, answer ID
	*
	* @return void
	*/
	public function downvoteAction($id) {

		$res = $this->answer->find($id);
		$this->vote->castVote($res, 'answer', 'downvotes', $res->getProperties()['questionId']);
	}


	/**
	 * Accept answer
	 *
	 * @param
	 *
	 * @return void
	 */
	 public function acceptAction($answerId) {

		 $now = date('Y-m-d H:i:s');
		 $ans = $this->answer->find($answerId);

		 // Check login for safety
		 $this->db->select("questionUserId")
			 ->from('question')
			 ->where("id = ?")
			 ->execute([$ans->questionId]);
		 $checkuser = $this->db->fetchAll();

		 if (!$this->di->UserloginController->checkLoginCorrectUser($checkuser[0]->questionUserId)) {
			 // Not logged in
			 $this->di->UserloginController->redirectToLogin('Logga in som rätt användare');
		 }

		 // reset any previously accepted answer
		 $unaccept = $this->answer->query()
			 ->where('questionId = ?')
			 ->andWhere('accepted IS NOT NULL')
			 ->execute([$ans->questionId]);

		 if ($unaccept) {
			 $this->unaccept($unaccept[0]->getProperties()['id']);
		 }
		 // Accept answer
		 $ans = $this->answer->find($answerId);
		 $ans->accepted = $now;
		 $ans->save();

		 $this->di->flashmessage->success('<p><span class="flashmsgicon"><i class="fa fa-check-circle fa-2x"></i></span> Accepterat svar</p>');
		 $url = $this->url->create('question/id/' . $ans->questionId);
		 $this->response->redirect($url);
	 }

	 /**
 	 * Unaccept answer
 	 *
 	 * @param int @answerId
 	 *
 	 * @return void
 	 */
 	 public function unacceptAction($answerId) {

		 $ans = $this->answer->find($answerId);

		 // Check login for safety
		 $this->db->select("questionUserId")
			 ->from('question')
			 ->where("id = ?")
			 ->execute([$ans->questionId]);
		 $checkuser = $this->db->fetchAll();

		 if (!$this->di->UserloginController->checkLoginCorrectUser($checkuser[0]->questionUserId)) {
			 // Not logged in
			 $this->di->UserloginController->redirectToLogin('Logga in som rätt användare');
		 }

		 $this->unaccept($answerId);

		 $this->di->flashmessage->info('<p><span class="flashmsgicon"><i class="fa fa-check-circle fa-2x"></i></span> Ångrade accepterat svar</p>');
		 $url = $this->url->create('question/id/'.$ans->questionId);
		 $this->response->redirect($url);
 	 }

	 /**
 	 * Unaccept answer
 	 *
 	 * @param int @answerId
 	 *
 	 * @return void
 	 */
 	 private function unaccept($answerId) {
		 $ans = $this->answer->find($answerId);
		 $ans->accepted = null;
 		 $ans->save();
 	 }


	/**
	 * Setup database
	 *
	 * @return void
	 *//*
	public function setupAction() {
		//$this->db->setVerbose();

		$this->db->dropTableIfExists('answer')->execute();

		$this->db->createTable(
			'answer',
			[
			'id' => ['integer', 'primary key', 'not null', 'auto_increment'],
			'content' => ['text'],
			'created' => ['datetime'],
			'updated' => ['datetime'],
			'deleted' => ['datetime'],
			'answerUserId' => ['integer', 'not null'],
			'questionId' => ['integer', 'not null'],
			'foreign key' => ['(answerUserId)', 'references', 'wgtotw_user(id)'],
			'foreign key' => ['(questionId)', 'references', 'wgtotw_question(id)'],
			]
			)->execute();

			$url = $this->url->create('question');
			$this->response->redirect($url);
	}
	*/
}
