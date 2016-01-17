<?php

namespace CR\Users;

/**
 *
 */
class UserloginController implements \Anax\DI\IInjectionAware {

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
    *
    * login
    *
    * @return void
    */
    public function loginAction() {
        if ($this->checkLoginSimple()) {
            $info = '<span class="flashmsgicon"><i class="fa fa-info-circle fa-2x"></i></span>&nbsp;Du är redan inloggad som '.$this->di->session->get('acronym');
            $this->di->flashmessage->info($info);
            $loginform = null;
        } else {
            $form = new \CR\HTMLForm\CFormLogin();
            $form->setDI($this->di);
            $form->check();
            $loginform = $form->getHTML();
        }

        $this->views->add('theme/index', [
			'title' => 'Logga in',
			'content' => '<h2>Logga in</h2>' . $this->di->flashmessage->outputMsgs(). $loginform,
        ], 'main-extended');
		//$this->views->add('users/users-sidebar', [], 'sidebar');
        $this->di->flashmessage->clearMessages();
    }

    /**
    * Logout
    *
    * @param bool $logout
    *
    * @return void
    */
    public function logoutAction() {

        if ($this->checkLoginSimple()) {
            $form = new \CR\HTMLForm\CFormConfirmLogout();
            $form->setDI($this->di);
            $form->check();
            $this->views->add('theme/index', [
                'title' => 'Utloggad',
                'content' => '<h3>Vill du logga ut?</h3>' . $form->getHTML(),
            ], 'main-extended');
        } else {
            $this->views->add('theme/index', [
                'title' => 'Utloggad',
                'content' => $this->di->flashmessage->outputMsgs()
            ], 'flash');
            $this->di->flashmessage->clearMessages();
        }
    }

    /**
    * Check login simple
    *
    * @return boolean
    */
    public function checkLoginSimple() {
        return $this->di->session->has('acronym');
    }

    /**
    * Check correct user login
    *
    * @param int $userId
    *
    * @return boolean
    */
    public function checkLoginCorrectUser($userId = null) {
        if ($this->di->session->has('acronym') && ($this->di->session->get('id') === $userId) || $this->di->session->get('isAdmin')) {
            return true;
        } else {
            return false;
        }
    }

    /**
    * Check admin user login
    *
    * @param int $userId
    *
    * @return boolean
    */
    public function checkLoginAdmin($userId = null) {
        if ($this->di->session->has('acronym') && $this->di->session->get('isAdmin')) {
            return true;
        } else {
            return false;
        }
    }

    /**
    * Redirect to login
    *
    * @param string $message, error message
    *
    * @return void
    */
    public function redirectToLogin($message = "Åtgärden kräver inloggning") {
        $this->di->flashmessage->error('<p><span class="flashmsgicon"><i class="fa fa-exclamation-triangle fa-2x"></i></span>&nbsp;'.$message.'</p>');
        $url = $this->url->create('login');
        $this->response->redirect($url);
    }
}
