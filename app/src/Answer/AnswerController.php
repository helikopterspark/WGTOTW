<?php

namespace CR\Answer;

/**
* A controller for Answer and CRUD related events.
*/
class AnswerController implements \Anax\DI\IInjectionAware {

	use \Anax\DI\TInjectable;

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
	 * @param integer $questionId
	 *
	 * @return void
	 */
	public function indexAction($questionId) {
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
			->where("questionId = " . $questionId)
			->groupBy("id")
			->orderBy($sorting)
			->execute();

		$all = $this->getRelatedData($all);

		$this->views->add('answer/heading', [
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
				$editform = true;
				$answerform = false;
				$this->edit($answer_post);
			} else {
			// Display answer
			$this->views->add('answer/index', [
				'content' => [$answer_post],
				'title' => 'svar',
			], 'main-extended');
		}
			// Get comments to answer
			$this->dispatcher->forward([
				'controller' => 'comments',
				'action'     => 'viewComments',
				'params'	=> [$answer_post->getProperties()['id'], 'answer', 'question'],
			]);
		}

		// Insert form for new answer if button is clicked and user is logged in
		if ($this->request->getGet('newanswer')) {
			if ($this->di->session->has('acronym')) {
				$answerform = true;
				$this->add($questionId);
			} else {
				// Not logged in
				$this->di->flashmessage->error('<p><span class="flashmsgicon"><i class="fa fa-exclamation-triangle fa-2x"></i></span>&nbsp;Logga in för att svara.</p>');
				$url = $this->url->create('login');
				$this->response->redirect($url);
			}

		} else {

		// Bottom view
		$this->views->add('answer/bottom', [
			'questionId' => $questionId,
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

		$res = $this->answer->delete($id);
	}
	/**
	* Get user data
	*
	* @param array $data questions fetched from db
	*
	* @return array $data questions with user data, answers and comments
	*/
	private function getRelatedData($data) {
		// If $data array not empty, convert question content from markdown to html, and get user data, Gravatars and tags
		if (is_array($data)) {
			foreach ($data as $id => &$answer) {
				$answer->getProperties()['content'] = $this->textFilter->doFilter($answer->getProperties()['content'], 'shortcode, markdown');
				$users = new \CR\Users\User();
				$users->setDI($this->di);
				$answer->user = $users->find($answer->getProperties()['answerUserId']);
				$answer->user->gravatar = 'http://www.gravatar.com/avatar/' . md5(strtolower(trim($answer->user->getProperties()['email']))) . '.jpg';
			}
		}
		return $data;
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
