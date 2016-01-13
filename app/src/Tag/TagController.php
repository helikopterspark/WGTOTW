<?php

namespace CR\Tag;

/**
* A controller for Tag and CRUD related events.
*/
class TagController implements \Anax\DI\IInjectionAware {

	use \Anax\DI\TInjectable;

	private $customhits = array(4, 8, 12);

	/**
	* Initialize the controller.
	*
	* @return void
	*/
	public function initialize() {
		$this->tag = new \CR\Tag\Tag();
		$this->tag->setDI($this->di);
	}

	/**
	* List all
	*
	* @return void
	*/
	public function indexAction($hits = 8, $page = 0) {

		$all = null;

		$all = $this->tag->query("t.*, COUNT(t2q.idQuestion) AS taggedquestions")
		->from('tag AS t')
		->join('tag2question AS t2q', 't.id = t2q.idTag')
		->limit($hits)
		->offset($page)
		->groupBy('t.id')
		->orderBy('t.name ASC')
		->execute();

		$count = $this->tag->query("COUNT(*) AS count")
			->execute();

		$pagelinks = $this->paginator->paginate($hits, $page, $count[0]->count, 'tag/index', $this->customhits);

		$this->theme->setTitle('Ämnen');
		$this->views->add('tag/index', [
			'content' => $all,
			'pages' => $pagelinks,
			'title' => 'Ämnen',
		], 'fullpage');
	}

	/**
	* Find with id.
	*
	* @param int $id
	*
	* @return void
	*/
	public function idAction($id = null) {

		$res = $this->tag->find($id);

		if ($res) {
			$this->theme->setTitle('Tag');
			$this->views->add('tag/view', [
				'content' => [$res],
				'title' => 'Tag Detail view',
			], 'main');
		} else {
			$url = $this->url->create('tag');
			$this->response->redirect($url);
		}
	}

	/**
	* Find most popular
	*
	* @param int $limit, no of post to fetch
	*
	* @return array $populartags, order by most popular
	*/
	public function getMostPopularTags($limit = 1) {
		$populartags = null;

		$this->tag = new \CR\Tag\Tag();
		$this->tag->setDI($this->di);

		$populartags = $this->tag->query("t.*, COUNT(t2q.idQuestion) AS taggedquestions")
		->from('tag AS t')
		->join('tag2question AS t2q', 't.id = t2q.idTag')
		->groupBy('t.id')
		->orderBy('taggedquestions DESC')
		->limit($limit)
		->execute();

		return $populartags;
	}

	/**
	* Add new
	*
	* @return void
	*/
	public function addAction() {
		/*
		$form = new \Anax\HTMLForm\CFormAddTag();
		$form->setDI($this->di);
		$form->check();

		$this->di->theme->setTitle('New');
		$this->views->add('tag/add', [
		'title' => 'New Tag',
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

	//$res = $this->tag->delete($id);
}


/**
* Setup database
*
* @return void
*/
public function setupAction() {
	//$this->db->setVerbose();
	/*
	$this->db->dropTableIfExists('tag2question')->execute();
	$this->db->dropTableIfExists('tag')->execute();

	$this->db->createTable(
	'tag',
	[
		'id' => ['integer', 'primary key', 'not null', 'auto_increment'],
		'name' => ['varchar(80)'],
		'description' => ['varchar(255)'],
		'created' => ['datetime'],
		'updated' => ['datetime'],
		'deleted' => ['datetime'],
	]
	)->execute();

	$this->db->createTable(
	'tag2question',
	[
		'idQuestion' => ['integer', 'not null'],
		'idTag' => ['integer', 'not null'],
		'foreign key' => ['(idQuestion)', 'references', 'wgtotw_question(id)'],
		'foreign key' => ['(idTag)', 'references', 'wgtotw_tag(id)'],
		'primary key' => ['(idQuestion, idTag)'],
	]
	)->execute();
	*/
}
}
