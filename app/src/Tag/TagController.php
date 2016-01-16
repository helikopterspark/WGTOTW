<?php

namespace CR\Tag;

/**
* A controller for Tag and CRUD related events.
*/
class TagController implements \Anax\DI\IInjectionAware {

	use \Anax\DI\TInjectable;

	private $customhits = array(4, 8, 16);

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
	* @param int $hits, number of hits per page
	* @param int $page, page for offset
	*
	* @return void
	*/
	public function indexAction($hits = 8, $page = 0) {

		$thits = $this->di->request->getGet('hits') ? $this->di->request->getGet('hits') : $this->di->session->get('thits');
		$thits = $thits != null ? $thits : 8;
		$this->di->session->set('thits', $thits);
		$page = $this->di->request->getGet('page') ? $this->di->request->getGet('page') : 0;

		$all = null;

		$all = $this->tag->query()
		->limit($thits)
		->offset($page)
		->where('deleted IS NULL')
		->groupBy('id')
		->orderBy('name ASC')
		->execute();;

		foreach ($all as $tag) {
			$this->db->select("COUNT(idQuestion) AS taggedquestions")
			->from('tag2question')
			->where('idTag = '.$tag->getProperties()['id'])
			->execute();
			$res = $this->db->fetchAll();
			$tag->setProperties(['taggedquestions' => $res[0]->taggedquestions]);
		}

		$count = $this->tag->query("COUNT(*) AS count")
		->where('deleted IS NULL')
		->execute();

		$get = array('hits' => $thits, 'page' => $page);
		$pagelinks = $this->paginator->paginateGet($count[0]->count, 'tag/index', $get, $this->customhits);

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
	public function getmostpopularAction($limit, $title) {
		$populartags = null;

		$populartags = $this->tag->query("t.*, COUNT(t2q.idQuestion) AS taggedquestions")
		->from('tag AS t')
		->join('tag2question AS t2q', 't.id = t2q.idTag')
		->where('t.deleted IS NULL')
		->groupBy('t.id')
		->orderBy('taggedquestions DESC')
		->limit($limit)
		->execute();

		$this->views->add('tag/side-shortlist', [
			'title' => $title,
			'content' => $populartags,
		], 'sidebar-reduced');
	}

	/**
	* Add new tag form
	*
	* @return void
	*/
	public function addAction() {

		if ($this->di->UserloginController->checkLoginAdmin($this->di->session->get('id'))) {

			$this->tag = $this->di->session->get('temptag');

			$form = new \CR\HTMLForm\CFormAddTag($this->tag);
			$form->setDI($this->di);
			$form->check();

			$this->di->theme->setTitle('Nytt ämne');
			$this->views->add('tag/add', [
				'title' => 'Nytt ämne',
				'content' => $this->di->flashmessage->outputMsgs() . $form->getHTML()
			], 'main-extended');
			$this->di->flashmessage->clearMessages();
		} else {
			$url = $this->url->create('tag');
			$this->response->redirect($url);
		}

	}

	/**
	* Add new tag form
	*
	* @return void
	*/
	public function updateAction($id = null) {

		if ($this->di->UserloginController->checkLoginAdmin($this->di->session->get('id'))) {

			$res = $this->tag->find($id);

			$form = new \CR\HTMLForm\CFormEditTag($res);
			$form->setDI($this->di);
			$form->check();

			$this->di->theme->setTitle('New');
			$this->views->add('tag/add', [
				'title' => 'Redigera ämne',
				'content' => $this->di->flashmessage->outputMsgs() . $form->getHTML()
			], 'main-extended');
			$this->di->flashmessage->clearMessages();
		} else {
			$url = $this->url->create('tag');
			$this->response->redirect($url);
		}

	}

	/**
	* Delete
	*
	* @param integer $id
	*
	* @return void
	*/
	public function deleteAction($id = null) {
		/*
		if (!isset($id)) {
			die('Missing id');
		}

		$res = $this->tag->delete($id);
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
