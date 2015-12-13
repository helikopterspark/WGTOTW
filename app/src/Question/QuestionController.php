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
		$this->questions = new \CR\Question\Question();
		$this->questions->setDI($this->di);
	}

	/**
	* List all questions
	*
	* @return void
	*/
	public function indexAction() {

		$all = null;
		$all = $this->questions->findAll();
		$all = $this->getRelatedData($all);

		$this->theme->setTitle('Frågor');
		$this->views->add('question/index', [
			'content' => $all,
			'title' => 'Frågor',
		], 'main-extended');
	}

	/**
	* List all questions with provided tag
	*
	* @param integer $tag id for tag
	*
	* @return void
	*/
	public function tagAction($tag = null) {

		$this->db->select("idQuestion")
		->from('tag2question')
		->where("idTag = ".$tag)
		;
		$this->db->execute();
		$taglist = $this->db->fetchAll();

		$this->db->select("name")
		->from('tag')
		->where("id = ".$tag)
		;
		$this->db->execute();
		$tagname = $this->db->fetchAll();
		
		foreach ($taglist as $value) {
			$q = new \CR\Question\Question();
			$q->setDI($this->di);
			$all[] = $q->find($value->idQuestion);
		}

		$all = $this->getRelatedData($all);

		$this->theme->setTitle('Frågor');
		$this->views->add('question/index', [
			'content' => $all,
			'title' => 'Frågor med taggen ' . $tagname[0]->name,
		], 'main-extended');
	}

	/**
	* Setup database
	*
	* @return void
	*/
	public function setupAction() {
		//$this->db->setVerbose();

		$this->db->dropTableIfExists('question')->execute();

		$this->db->createTable(
		'question',
		[
			'id' => ['integer', 'primary key', 'not null', 'auto_increment'],
			'title' => ['varchar(80)'],
			'data' => ['text'],
			'created' => ['datetime'],
			'updated' => ['datetime'],
			'deleted' => ['datetime'],
			'upvotes' => ['integer'],
			'downvotes' => ['integer'],
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

		$res = $this->questions->find($id);

		if ($res) {
			$this->theme->setTitle('Question');
			$this->views->add('question/view', [
				'content' => [$res],
				'title' => 'Question Detail view',
			], 'main');
		} else {
			$url = $this->url->create('question');
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

	$res = $this->questions->delete($id);
}

/**
* Get user data, answers and comments
*
* @param array $data questions fetched from db
*
* @return array $data questions with user data, answers and comments
*/
private function getRelatedData($data) {
	// If $all array not empty, convert question content from markdown to html, and get user data, Gravatars and tags
	if (is_array($data)) {
		foreach ($data as $id => &$question) {
			$question->getProperties()['data'] = $this->textFilter->doFilter($question->getProperties()['data'], 'shortcode, markdown');
			$users = new \CR\Users\User();
			$users->setDI($this->di);
			$question->user = $users->find($question->getProperties()['questionUserId']);
			$question->user->gravatar = 'http://www.gravatar.com/avatar/' . md5(strtolower(trim($question->user->getProperties()['email']))) . '.jpg';
			$this->db->select("idTag")
			->from('tag2question')
			->where("idQuestion = ".$question->getProperties()['id'])
			;
			$this->db->execute();
			$taglist = $this->db->fetchAll();

			$question->tags = array();
			foreach ($taglist as $value) {
				$tag = new \CR\Tag\Tag();
				$tag->setDI($this->di);
				$question->tags[] = $tag->find($value->idTag);
			}
		}
	}
	return $data;
}
}
