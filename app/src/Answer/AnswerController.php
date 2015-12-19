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
		foreach ($all as $answerC) {
			$this->views->add('answer/index', [
				'content' => [$answerC],
				'title' => 'svar',
			], 'main-extended');
			$this->dispatcher->forward([
				'controller' => 'comments',
				'action'     => 'viewComments',
				'params'	=> [$answerC->getProperties()['id'], 'answer', 'question'],
			]);
		}
		$this->views->add('answer/bottom', [
		], 'main-extended');
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
				$answer->getProperties()['data'] = $this->textFilter->doFilter($answer->getProperties()['data'], 'shortcode, markdown');
				$users = new \CR\Users\User();
				$users->setDI($this->di);
				$answer->user = $users->find($answer->getProperties()['answerUserId']);
				$answer->user->gravatar = 'http://www.gravatar.com/avatar/' . md5(strtolower(trim($answer->user->getProperties()['email']))) . '.jpg';
			}
		}
		return $data;
	}
}
