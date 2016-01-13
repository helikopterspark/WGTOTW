<?php

namespace CR\Question;

/**
* A controller for Question and CRUD related events.
*/
class QuestionController implements \Anax\DI\IInjectionAware {

	use \Anax\DI\TInjectable;

	private $customhits = array(5, 10, 15);

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
	* @param int $hits, number of hits per page
	* @param int $page, page for offset
	*
	* @return void
	*/
	public function indexAction($hits = null, $page = 0) {
		// Check hits-per-page preference in session
		if (!$hits) {
			if ($this->di->session->has('qhits')) {
				$qhits = $this->di->session->get('qhits');
			} else {
				$qhits = 10;
			}
		} else {
			$qhits = $hits;
			$this->di->session->set('qhits', $qhits);
		}

		$all = $this->questions->query()
			->orderBy('created DESC')
			->limit($qhits)
			->offset($page)
			->execute();
		$all = $this->getRelatedData($all);

		$count = $this->questions->query("COUNT(*) AS count")
			->execute();

		$pagelinks = $this->paginator->paginate($qhits, $page, $count[0]->count, 'question/index', $this->customhits);

		$this->theme->setTitle('Alla frågor');
		$this->views->add('question/index', [
			'content' => $all,
			'pages' => $pagelinks,
			'title' => 'Alla frågor',
		], 'main-extended');

		$populartags = $this->TagController->getMostPopularTags(6);

		$title = $count[0]->count == 1 ? $count[0]->count .' fråga' : $count[0]->count .' frågor';

		$this->views->add('tag/side-shortlist', [
			'title' => $title,
			'content' => $populartags,
		], 'sidebar-reduced');
	}

	/**
	* List all questions with provided tag
	*
	* @param integer $tag id for tag
	* @param int $hits, number of hits per page
	* @param int $page, page for offset
	*
	* @return void
	*/
	public function tagAction($tag = null, $hits = null, $page = 0) {
		// Check hits-per-page preference in session
		if (!$hits) {
			if ($this->di->session->has('qhits')) {
				$qhits = $this->di->session->get('qhits');
			} else {
				$qhits = 10;
			}
		} else {
			$qhits = $hits;
			$this->di->session->set('qhits', $qhits);
		}

		$t = new \CR\Tag\Tag();
		$t->setDI($this->di);
		$tagname = $t->find($tag);

		$count = $this->questions->query("COUNT(*) AS count")
		->from('question AS q')
		->join('tag2question AS t2q', 'q.id = t2q.idQuestion')
		->where("t2q.idTag = " . $tag)
		->execute();

		$pagelinks = $this->paginator->paginate($qhits, $page, $count[0]->count, 'question/tag/'.$tag, $this->customhits);

		$all = $this->questions->query("q.*")
		->from('question AS q')
		->join('tag2question AS t2q', 'q.id = t2q.idQuestion')
		->where("t2q.idTag = " . $tag)
		->limit($qhits)
		->offset($page)
		->groupBy('q.id')
		->orderBy('q.created DESC')
		->execute();
		$all = $this->getRelatedData($all);

		$this->theme->setTitle($tagname->getProperties()['name']);
		$this->views->add('question/index', [
			'content' => $all,
			'pages' => $pagelinks,
			'title' => 'Frågor om ' . $tagname->getProperties()['name'],
		], 'main-extended');

		//$title = count($all) == 1 ? count($all) .' fråga' : count($all) .' frågor';
		$title = $count[0]->count == 1 ? $count[0]->count .' fråga' : $count[0]->count .' frågor';
		$this->views->add('tag/view', [
			'title' => $title,
			'tag' => $tagname,
		], 'sidebar-reduced');
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
			$res = $this->getRelatedData([$res]);
			$vote = $this->vote->checkVote($res[0], 'question');

			$this->theme->setTitle($res[0]->getProperties()['title']);
			$this->views->add('question/view', [
				'flash' => $this->di->flashmessage->outputMsgs(),
				'question' => $res[0],
				'title' => $res[0]->getProperties()['title'],
				'vote' => $vote,
			], 'main-extended');
			$this->di->flashmessage->clearMessages();
			$this->dispatcher->forward([
     			'controller' => 'comments',
     			'action'     => 'viewComments',
     			'params'	=> [$id, 'question', $id],
			]);
			$this->dispatcher->forward([
				'controller' => 'answer',
				'action'	=> 'index',
				'params'	=> [$res[0]],
			]);
			$this->views->add('tag/side-view', [
				'title' => 'Relaterade ämnen',
				'content' => $res[0]->tags,
			], 'sidebar-reduced');
		} else {
			$url = $this->url->create('question');
			$this->response->redirect($url);
		}
	}

	/**
	* Search action, search for questions with searchstring
	*
	* @param int $hits, number of hits per page
	* @param int $page, page for offset
	*
	* @return void
	*/
	public function searchAction($hits = null, $page = 0) {
		// For pagination, check for new searchstring or use saved in session
		$searchstring = $this->request->getPost('search') ? $this->request->getPost('search') : $this->di->session->get('search');
		$this->di->session->set('search', $searchstring);
		// Check hits-per-page preference in session
		if (!$hits) {
			if ($this->di->session->has('qhits')) {
				$qhits = $this->di->session->get('qhits');
			} else {
				$qhits = 10;
			}
		} else {
			$qhits = $hits;
			$this->di->session->set('qhits', $qhits);
		}

		$res = $this->questions->query()
			->where("title LIKE '%".$searchstring."%' OR content LIKE '%".$searchstring."%'")
			->limit($qhits)
			->offset($page)
			->orderBy('created DESC')
			->execute();

		$res = $this->getRelatedData($res);

		$count = $this->questions->query("COUNT(*) AS count")
			->where("title LIKE '%".$searchstring."%' OR content LIKE '%".$searchstring."%'")
			->execute();

		$pagelinks = $this->paginator->paginate($qhits, $page, $count[0]->count, 'question/search', $this->customhits);

		$this->theme->setTitle('Sökresultat för '.$searchstring);
		$this->views->add('question/index', [
			'content' => $res,
			'pages' => $pagelinks,
			'title' => 'Sökresultat för '.$searchstring,
		], 'main-extended');
		$title = $count[0]->count == 1 ? $count[0]->count .' fråga' : $count[0]->count .' frågor';
		$this->views->add('tag/view', [
			'title' => $title,
			'tag' => null,
		], 'sidebar-reduced');
	}

	/**
	* Upvote action
	*
	* @param string $id, question ID
	*
	* @return void
	*/
	public function upvoteAction($id) {

		$res = $this->questions->find($id);
		$this->vote->castVote($res, 'question', 'upvotes', $id);
	}

	/**
	* Downvote action
	*
	* @param string $id, question ID
	*
	* @return void
	*/
	public function downvoteAction($id) {

		$res = $this->questions->find($id);
		$this->vote->castVote($res, 'question', 'downvotes', $id);
	}

	/**
	* Add new question
	*
	* @return void
	*/
	public function addAction() {

		if (!$this->di->UserloginController->checkLoginSimple()) {
			// Not logged in
			$this->di->UserloginController->redirectToLogin('Logga in för att ställa en fråga');

		} else {

			$tag = new \CR\Tag\Tag();
			$tag->setDI($this->di);
	        $tags = $tag->findAll();

			$form = new \CR\HTMLForm\CFormAddQuestion($tags);
			$form->setDI($this->di);
			$form->check();

			$this->di->theme->setTitle('Ny fråga');
			$this->views->add('theme/index', [
				'title' => 'Ny fråga',
				'content' => '<h2>Ny fråga</h2>' . $form->getHTML(),
			], 'main-extended');
		}
	}

	/**
	* Update question
	*
	* @return void
	*/
	public function updateAction($id = null) {
		$qstn = $this->questions->find($id);
		$qstn = $this->getRelatedData([$qstn]);

		if ($this->di->UserloginController->checkLoginCorrectUser($qstn[0]->user->getProperties()['id'])) {
			$tag = new \CR\Tag\Tag();
			$tag->setDI($this->di);
	        $tags = $tag->findAll();

			$form = new \CR\HTMLForm\CFormEditQuestion($qstn[0], $tags);
			$form->setDI($this->di);
			$form->check();

			$this->di->theme->setTitle('Redigera fråga');
			$this->views->add('theme/index', [
				'title' => 'Redigera fråga',
				'content' => '<h2>Redigera fråga</h2>' . $form->getHTML(),
			], 'main-extended');

		} else {
			// Not logged in
			$this->di->UserloginController->redirectToLogin('Logga in som '. $qstn[0]->user->getProperties()['acronym'] . ' för att redigera fråga');
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
	if (!isset($id)) {
		die('Missing id');
	}

	//$res = $this->questions->delete($id);
}

/**
* Get user data, tags, answers and comments
*
* @param array $data questions fetched from db
*
* @return array $data questions with user data, answers and comments
*/
public function getRelatedData($data) {
	// If $data array not empty, convert question content from markdown to html, and get user data, Gravatars and tags
	if (is_array($data)) {
		foreach ($data as $id => &$question) {

			$question->filteredcontent = $this->textFilter->doFilter($question->getProperties()['content'], 'shortcode, markdown');
			// Get user info
			$users = new \CR\Users\User();
			$users->setDI($this->di);
			$question->user = $users->find($question->getProperties()['questionUserId']);
			$question->user->gravatar = 'http://www.gravatar.com/avatar/' . md5(strtolower(trim($question->user->getProperties()['email']))) . '.jpg?d=identicon';
			$question->user->stats = $this->UsersController->getUserStats($question->getProperties()['questionUserId']);
			// Get associated tags
			$tagIDlist = $this->getSelectedTagIDs($question->getProperties()['id']);
			$question->tags = array();
			foreach ($tagIDlist as $value) {
				$tag = new \CR\Tag\Tag();
				$tag->setDI($this->di);
				$question->tags[] = $tag->find($value->idTag);
			}
			// Sort tags in alphabetical order by name
			usort($question->tags, function($a, $b) {
				return strcmp($a->name, $b->name);
			});
			// Get no of answers to question
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

/**
* Get selected tags for a question
*
* @param integer $id, question ID
*
* @return array $tagIDlist
*/
private function getSelectedTagIDs($id) {
	$this->db->select("idTag")
		->from('tag2question')
		->where("idQuestion = ?")
		->execute([$id]);
	$tagIDlist = $this->db->fetchAll();

	return $tagIDlist;
}

/**
* Setup database
*
* @return void
*/
public function setupAction() {
	//$this->db->setVerbose();
/*
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
	*/
}
}
