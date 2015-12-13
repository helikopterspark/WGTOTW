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
	 * List all
	 *
	 * @return void
	 */
	public function indexAction() {

		$all = null;
		//$all = $this->Answer->findAll();

		$this->theme->setTitle('Answer');
		$this->views->add('answer/index', [
			'content' => $all,
			'title' => 'Answer',
			], 'main');
	}

	/**
	 * Setup database
	 *
	 * @return void
	 */
	public function setupAction() {
		//$this->db->setVerbose();

		$this->db->dropTableIfExists('answer')->execute();

		$this->db->createTable(
			'answer',
			[
			'id' => ['integer', 'primary key', 'not null', 'auto_increment'],
			'data' => ['text'],
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
	 * Add new
	 *
	 * @return void
	 */
	public function addAction() {
/*
		$form = new \Anax\HTMLForm\CFormAddAnswer();
		$form->setDI($this->di);
		$form->check();

		$this->di->theme->setTitle('New');
		$this->views->add('Answer/add', [
			'title' => 'New Answer',
			'content' => $form->getHTML()
			], 'main');
*/
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
}
