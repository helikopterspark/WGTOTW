<?php

namespace CR\HTMLForm;

/**
 * Logout confirm form
 *
 */
class CFormConfirmLogout extends \Mos\HTMLForm\CForm
{
    use \Anax\DI\TInjectionaware,
    \Anax\MVC\TRedirectHelpers;

    private $error;
    private $url;

    /**
     * Constructor
     *
     */
    public function __construct()
    {
        $this->error = '<span class="flashmsgicon"><i class="fa fa-times-circle fa-2x"></i></span>&nbsp;';

        parent::__construct(['id' => 'confirm-logout-form', 'class' => 'confirm-logout-form'], [

            'submit' => [
            'type'      => 'submit',
            'value'     => 'Logga ut',
            'callback'  => [$this, 'callbackSubmit'],
            ],
            'submit-abort' => [
            'type'      => 'submit',
            'value'     => 'Avbryt',
            'formnovalidate' => true,
            'callback'  => [$this, 'callbackSubmitFail'],
            ],

            ]);
}



    /**
     * Customise the check() method.
     *
     * @param callable $callIfSuccess handler to call if function returns true.
     * @param callable $callIfFail    handler to call if function returns true.
     */
    public function check($callIfSuccess = null, $callIfFail = null)
    {
        if ($this->di->request->getPost('submit-abort')) {
            $id = $this->di->session->get('id');
            $url = $this->di->url->create('users/id/'.$id);
            $this->redirectTo($url);
        } else {
            return parent::check([$this, 'callbackSuccess'], [$this, 'callbackFail']);
        }
    }



    /**
     * Callback for submit-button.
     *
     */
    public function callbackSubmit()
    {
        if ($this->di->UserloginController->checkLoginSimple()) {
            $this->di->session->set('acronym', null);
            $this->di->session->set('id', null);
            $this->di->session->set('email', null);
            $this->di->session->set('isAdmin', null);
            $this->di->session->set('colortheme', null);
        }

        return true;
    }



    /**
     * Callback for submit-button.
     *
     */
    public function callbackSubmitFail()
    {
        $this->AddOutput("<p><i>DoSubmitFail(): Form was submitted but I failed to process/save/validate it</i></p>");
        return false;
    }



    /**
     * Callback What to do if the form was submitted?
     *
     */
    public function callbackSuccess()
    {
        $info = '<span class="flashmsgicon"><i class="fa fa-info-circle fa-2x"></i></span>&nbsp;Du är utloggad.';
        $this->di->flashmessage->info($info);
        $url = $this->di->url->create('logout');
        $this->redirectTo($url);
    }



    /**
     * Callback What to do when form could not be processed?
     *
     */
    public function callbackFail()
    {
        $this->di->flashmessage->error($this->error);
        $this->redirectTo();
    }

}
