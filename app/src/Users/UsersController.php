<?php

namespace CR\Users;

/**
* A controller for users and admin related events.
*/
class UsersController implements \Anax\DI\IInjectionAware {

	use \Anax\DI\TInjectable;

	/**
	 * Initialize the controller.
	 *
	 * @return void
	 */
	public function initialize() {
		$this->users = new \CR\Users\User();
		$this->users->setDI($this->di);
	}

	/**
	 * List all users.
	 *
	 * @return void
	 */
	public function indexAction() {

		$all = $this->users->findAll();

		$this->theme->setTitle("Alla användare");
		$this->views->add('users/list-all', [
			'users' => $all,
			'title' => "Alla användare",
			], 'main');
		$this->views->add('users/users-sidebar', [], 'sidebar');
		$this->addLegend();
	}

	/**
	* Add legend as triptych
	*
	* @return
	*/
	private function addLegend() {
		$legend1 = $this->fileContent->get('users/users-legend.html');
		$legend2 = $this->fileContent->get('users/users-legend-2.html');
		$legend3 = $this->fileContent->get('users/users-legend-3.html');

		$this->theme->addClassAttributeFor('wrap-triptych', 'smaller-text');

		$this->views->add('theme/region-small', ['content' => $legend1], 'triptych-1');
		$this->views->add('theme/region-small', ['content' => $legend2], 'triptych-2');
		$this->views->add('theme/region-small', ['content' => $legend3], 'triptych-3');
	}

	/**
	 * List user with id.
	 *
	 * @param int $id of user to display
	 *
	 * @return void
	 */
	public function idAction($id = null) {

		$user = $this->users->find($id);

		if ($user) {
			$this->theme->setTitle("Användare " . $user->acronym);
			$this->views->add('users/view', [
				'users' => $user,
				'title' => 'Användare ' . $user->acronym,
				'flash' => $this->di->flashmessage->outputMsgs(),
			], 'fullpage');
			//$this->views->add('users/users-sidebar', [], 'sidebar');
			$this->di->flashmessage->clearMessages();
		} else {
			$url = $this->url->create('users');
			$this->response->redirect($url);
		}
	}

	/**
	 * Add new user.
	 *
	 * @param string $acronym of user to add.
	 *
	 * @return void
	 */
	public function addAction($acronym = null) {

		$this->users = $this->di->session->get('tempuser');

		$form = new \CR\HTMLForm\CFormAddUser($this->users);
		$form->setDI($this->di);
		$form->check();

		$this->di->theme->setTitle("Registrera användare");
		$this->views->add('theme/index', [
			'title' => 'Registrera användare',
			'content' => '<h2>Registrera användare</h2>' . $this->di->flashmessage->outputMsgs() . $form->getHTML()
			], 'main');
		$this->views->add('users/users-sidebar', [], 'sidebar');
		$this->di->flashmessage->clearMessages();
	}

	/**
	 * Update user.
	 *
	 * @param int $id of user to add.
	 *
	 * @return void
	 */
	public function updateAction($id = null) {
		$content = null;
		if (!$this->di->session->has('acronym')) {
			// Not logged in
			$this->di->flashmessage->error('<p><span class="flashmsgicon"><i class="fa fa-exclamation-triangle fa-2x"></i></span>&nbsp;Logga in för att redigera.</p>');
			$url = $this->url->create('login');
			$this->response->redirect($url);

		} elseif ($this->di->session->has('acronym') && ($this->di->session->get('id') === $id) || $this->di->session->get('isAdmin')) {
			// User is logged in, show update form
			$user = $this->users->find($id);
			$form = new \CR\HTMLForm\CFormEditUser($user);
			$form->setDI($this->di);
			$form->check();

			$content = $form->getHTML();

		} else {
			// Wrong user is logged in
			//$this->di->flashmessage->error('Fel användare är inloggad.');
			$content = '<p>Den gubben går inte! Fel användare är inloggad.</p>';

		}

		$this->di->theme->setTitle("Uppdatera användare");
		$this->views->add('theme/index', [
			'title' => 'Uppdatera användare',
			'content' => '<h2>Uppdatera användare</h2>' . $this->di->flashmessage->outputMsgs() . $content
		], 'fullpage');
		//$this->views->add('users/users-sidebar', [], 'sidebar');
		$this->di->flashmessage->clearMessages();
	}

	/**
	 * Delete user.
	 *
	 * @param integer $id of user to delete.
	 *
	 * @return void
	 */
	public function deleteAction($id = null) {
		if (!isset($id)) {
			die("Missing id");
		}

		$res = $this->users->delete($id);

		$url = $this->url->create('users');
		$this->response->redirect($url);
	}

	/**
	 * Delete (soft) user.
	 *
	 * @param integer $id of user to soft delete.
	 *
	 * @return void
	 */
	public function softDeleteAction($id = null) {
		if (!isset($id)) {
			die("Missing id");
		}

		$now = date('Y-m-d H:i:s');

		$user = $this->users->find($id);

		$user->deleted = $now;
		$user->active = null;
		$user->save();

		$url = $this->url->create('users/id/' . $id);
		$this->response->redirect($url);
	}

	/**
	 * Undo delete (soft) user.
	 *
	 * @param integer $id of user to undo soft delete.
	 *
	 * @return void
	 */
	public function undoSoftDeleteAction($id = null) {
		if (!isset($id)) {
			die("Missing id");
		}

		$now = date('Y-m-d H:i:s');

		$user = $this->users->find($id);

		$user->deleted = null;
		//$user->active = $now;
		$user->save();

		$url = $this->url->create('users/id/' . $id);
		$this->response->redirect($url);
	}

	/**
	 * List all active and not deleted users.
	 *
	 * @return void
	 */
	public function activeAction() {
		$all = $this->users->query()
		->where('active IS NOT NULL')
		->andWhere('deleted IS NULL')
		->execute();

		$this->theme->setTitle("Aktiva användare");
		$this->views->add('users/list-all', [
			'users' => $all,
			'title' => "Aktiva användare",
			], 'main');
		$this->views->add('users/users-sidebar', [], 'sidebar');
		$this->addLegend();
	}

	/**
	 * List all active and not deleted users.
	 *
	 * @return void
	 */
	public function inactiveAction() {
		$all = $this->users->query()
		->where('active IS NULL')
		->andWhere('deleted IS NULL')
		->execute();

		$this->theme->setTitle("Inaktiva användare");
		$this->views->add('users/list-all', [
			'users' => $all,
			'title' => "Inaktiva användare",
			], 'main');
		$this->views->add('users/users-sidebar', [], 'sidebar');
		$this->addLegend();
	}

	/**
	 * List all active and not deleted users.
	 *
	 * @return void
	 */
	public function nonDeletedAction() {
		$all = $this->users->query()
		->where('deleted IS NULL')
		->execute();

		$this->theme->setTitle("Aktiva och inaktiva användare");
		$this->views->add('users/list-all', [
			'users' => $all,
			'title' => "Aktiva och inaktiva användare",
			], 'main');
		$this->views->add('users/users-sidebar', [], 'sidebar');
		$this->addLegend();
	}

	/**
	 * List all trashed (softdeleted) users.
	 *
	 * @return void
	 */
	public function trashAction() {
		$all = $this->users->query()
		->where('deleted IS NOT NULL')
		->execute();

		$this->theme->setTitle("Papperskorgen");
		$this->views->add('users/list-all', [
			'users' => $all,
			'title' => "Papperskorgen",
			], 'main');
		$this->views->add('users/users-sidebar', [], 'sidebar');
		$this->addLegend();
	}

	/**
	 * Setup database
	 *
	 * @return void
	 */
	public function setupAction() {
		//$this->db->setVerbose();
		/*
		$this->db->dropTableIfExists('user')->execute();

		$this->db->createTable(
			'user',
			[
			'id' => ['integer', 'primary key', 'not null', 'auto_increment'],
			'acronym' => ['varchar(20)', 'unique', 'not null'],
			'email' => ['varchar(80)'],
			'name' => ['varchar(80)'],
			'password' => ['varchar(255)'],
			'created' => ['datetime'],
			'updated' => ['datetime'],
			'deleted' => ['datetime'],
			'active' => ['datetime'],
			]
			)->execute();
		*/
		$this->db->insert(
			'user',
			['acronym', 'email', 'name', 'password', 'created', 'updated', 'active', 'deleted']
			);

		$now = date('Y-m-d H:i:s');

		$enc_password = $this->encryptPassword('admin');
		$this->db->execute([
			'admin',
			'admin@dbwebb.se',
			'Administrator',
			$enc_password,
			$now,
			null,
			$now,
			null
			]);

		$enc_password = $this->encryptPassword('johndoe');
		$this->db->execute([
			'johndoe',
			'johndoe@dbwebb.se',
			'John Doe',
			$enc_password,
			$now,
			null,
			$now,
			null
			]);

		$enc_password = $this->encryptPassword('janedoe');
		$this->db->execute([
			'janedoe',
			'janedoe@dbwebb.se',
			'Jane Doe',
			$enc_password,
			$now,
			null,
			null,
			null
			]);

		$enc_password = $this->encryptPassword('nisse');
		$this->db->execute([
			'nisse',
			'nisse@dbwebb.se',
			'Nisse Hulth',
			$enc_password,
			$now,
			null,
			null,
			$now
			]);

		$url = $this->url->create('users');
		$this->response->redirect($url);
	}

	/**
	 * Encrypt password depending on PHP version
	 *
	 * @param string $password, password as string
	 *
	 * @return string $enc_password, encrypted password
	 */
	private function encryptPassword($password) {
		if (version_compare(phpversion(), '5.5.0', '<')) {
            $enc_password = md5($password);
        } else {
            $enc_password = password_hash($password, PASSWORD_DEFAULT);
        }

		return $enc_password;
	}
}
