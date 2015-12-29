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
        if ($this->di->session->has('acronym')) {
            $info = '<span class="flashmsgicon"><i class="fa fa-info-circle fa-2x"></i></span>&nbsp;Du är redan inloggad.';
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
			'content' => '<h2>Logga in</h2>' . $this->di->flashmessage->outputMsgs(). $loginform
        ], 'main-extended');
		//$this->views->add('users/users-sidebar', [], 'sidebar');
        $this->di->flashmessage->clearMessages();
    }

    /**
    * Logout
    *
    * @param string $acronym, user acronym
    *
    * @return void
    */
    public function logoutAction() {
        if ($this->di->session->has('acronym')) {
            $this->di->session->set('acronym', null);
            $this->di->session->set('id', null);
            $this->di->session->set('email', null);
            $this->di->session->set('isAdmin', null);

            $info = '<span class="flashmsgicon"><i class="fa fa-info-circle fa-2x"></i></span>&nbsp;Du är utloggad.';
            $this->di->flashmessage->info($info);
            $url = $this->url->create('logout');
    		$this->response->redirect($url);
        } else {
            $this->views->add('theme/index', [
                'title' => 'Utloggad',
                'content' => $this->di->flashmessage->outputMsgs()
            ], 'flash');

            $this->di->flashmessage->clearMessages();
        }
    }
}
