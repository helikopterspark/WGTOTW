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

		$this->theme->setTitle('Alla frågor');
		$this->views->add('question/index', [
			'content' => $all,
			'title' => 'Alla frågor',
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

		$t = new \CR\Tag\Tag();
		$t->setDI($this->di);
		$tagname = $t->find($tag);

		$all = $this->questions->query("q.*")
		->from('question AS q')
		->join('tag2question AS t2q', 'q.id = t2q.idQuestion')
		->where("t2q.idTag = " . $tag)
		->groupBy('q.id')
		->execute();
		$all = $this->getRelatedData($all);

		$this->theme->setTitle($tagname->name);
		$this->views->add('question/index', [
			'content' => $all,
			'title' => 'Frågor om ' . $tagname->name,
		], 'main-extended');
		$this->views->add('theme/index', [
			'content' => '<h3>'.$tagname->name.'</h3><p>'.$tagname->description .'</p>',
		], 'sidebar-reduced');
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
		$res = $this->getRelatedData([$res]);

		if ($res) {
			$this->theme->setTitle($res[0]->getProperties()['title']);
			$this->views->add('question/view', [
				'question' => $res[0],
				'title' => $res[0]->getProperties()['title'],
			], 'main-extended');
			$this->dispatcher->forward([
     			'controller' => 'comments',
     			'action'     => 'viewComments',
     			'params'	=> [$id, 'question', 'question'],
			]);
			$this->dispatcher->forward([
				'controller' => 'answer',
				'action'	=> 'index',
				'params'	=> [$id],
			]);
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
	public function askAction() {
		/*
		$form = new \Anax\HTMLForm\CFormAskQuestion();
		$form->setDI($this->di);
		$form->check();

		$this->di->theme->setTitle('Ny fråga');
		$this->views->add('question/add', [
		'title' => 'Ny fråga',
		'content' => $form->getHTML()
	], 'main-extended');
	*/
		$this->di->theme->setTitle('Ny fråga');
		$this->views->add('theme/index', [
			'title' => 'Ny fråga',
			'content' => '<h2>Ny fråga</h2>'
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

	$res = $this->questions->delete($id);
}

/**
* Get user data, tags, answers and comments
*
* @param array $data questions fetched from db
*
* @return array $data questions with user data, answers and comments
*/
private function getRelatedData($data) {
	// If $data array not empty, convert question content from markdown to html, and get user data, Gravatars and tags
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
			->execute();
			$taglist = $this->db->fetchAll();

			$question->tags = array();
			foreach ($taglist as $value) {
				$tag = new \CR\Tag\Tag();
				$tag->setDI($this->di);
				$question->tags[] = $tag->find($value->idTag);
			}
			$this->db->select("COUNT(*) AS noOfAnswers")
			->from('answer')
			->where("questionId = ".$question->getProperties()['id'])
			->execute();

			$res = $this->db->fetchAll();
			$question->noOfAnswers = $res[0]->noOfAnswers;
		}
	}
	return $data;
}
}
