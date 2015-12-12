<?php

namespace CR\Question;

/**
* A controller for Question and CRUD related events.
*/
class QuestionController implements \Anax\DI\IInjectionAware {

	use \Anax\DI\TInjectable;

	/**
	* Initialize the controller.
	*
	* @return void
	*/
	public function initialize() {
		$this->Question = new \CR\Question\Question();
		$this->Question->setDI($this->di);
	}

	/**
	* List all
	*
	* @return void
	*/
	public function indexAction() {

		$all = null;
		//$all = $this->Question->findAll();

		$this->theme->setTitle('Question');
		$this->views->add('Question/index', [
			'content' => $all,
			'title' => 'Question',
		], 'main');
	}

	/**
	* Setup database
	*
	* @return void
	*/
	public function setupAction() {
		//$this->db->setVerbose();

		$this->db->dropTableIfExists('Question')->execute();

		$this->db->createTable(
		'question',
		[
			'id' => ['integer', 'primary key', 'not null', 'auto_increment'],
			'data' => ['text'],
			'created' => ['datetime'],
			'updated' => ['datetime'],
			'deleted' => ['datetime'],
			'questionUserId' => ['integer', 'not null'],
			'foreign key' => ['(questionUserId)', 'references', 'wgtotw_user(id)'],
		]
		)->execute();

		$url = $this->url->create('answer/setup');
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

		$res = $this->Question->find($id);

		if ($res) {
			$this->theme->setTitle('Question');
			$this->views->add('Question/view', [
				'content' => [$res],
				'title' => 'Question Detail view',
			], 'main');
		} else {
			$url = $this->url->create('Question-');
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
		$form = new \Anax\HTMLForm\CFormAddQuestion();
		$form->setDI($this->di);
		$form->check();

		$this->di->theme->setTitle('New');
		$this->views->add('Question/add', [
		'title' => 'New Question',
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

	$res = $this->Question->delete($id);
}
}
