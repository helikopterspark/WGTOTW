<?php

namespace CR\HTMLForm;

/**
 * Anax base class for wrapping sessions.
 *
 */
class CFormLogin extends \Mos\HTMLForm\CForm
{
    use \Anax\DI\TInjectionaware,
    \Anax\MVC\TRedirectHelpers;

    private $error;
    private $id = null;

    /**
     * Constructor
     *
     */
    public function __construct()
    {
        $this->error = '<span class="flashmsgicon"><i class="fa fa-times-circle fa-2x"></i></span>&nbsp;';

        parent::__construct(['id' => 'login-form', 'class' => 'login-form'], [
            'acronym' => [
            'type'          => 'text',
            'label'         => 'Användarnamn:',
            'autofocus'     => true,
            'required'      => true,
            'validation'    => ['not_empty'],
            ],

            'password' => [
            'type'          => 'password',
            'label'         => 'Lösenord',
            'required'      => true,
            'validation'    => ['not_empty'],
            ],
            'submit' => [
            'type'      => 'submit',
            'value'     => 'Logga in',
            'callback'  => [$this, 'callbackSubmit'],
            ],
            'submit-add' => [
            'type'      => 'submit',
            'value'     => 'Bli medlem',
            'formnovalidate' => true,
            'callback'  => [$this, 'callbackSubmit'],
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
        if ($this->di->request->getPost('submit-add')) {
            $this->redirectTo('users/add');
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

        $this->user = new \CR\Users\User();
        $this->user->setDI($this->di);

        $login = $this->user->query()
            ->where('acronym = ?')
            ->execute([$this->Value('acronym')]);

        if ($login) {
            // acronym exists so check password, returns true or false
            return $this->verifyPassword($login[0], $this->Value('password'));
        } else {
            // acronym does not exist
            $this->error .= 'Användarnamnet finns inte.';
            return false;
        }
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
        //$this->AddOUtput("<p><i>Användaren " . $this->user->acronym . " registrerades</i></p>");
        $this->redirectTo('users/id/' . $this->id);
    }



    /**
     * Callback What to do when form could not be processed?
     *
     */
    public function callbackFail()
    {
        $this->di->flashmessage->error($this->error);
        //$this->AddOutput("<p><i>Det gick inte att spara. Kontrollera fälten.</i></p>");
        $this->redirectTo();
    }

    /**
    * Verify password
    *
    * @param User object $user
    * @param string @password, entered password
    *
    * @return true or false
    */
    private function verifyPassword($user, $password) {
        if (version_compare(phpversion(), '5.5.0', '<')) {
            $verify_password = (md5($password) == $user->password) ? true : false;
        } else {
            $verify_password = password_verify($password, $user->password);
        }

        if ($verify_password) {
            $this->id = $user->id;
            $this->di->session->set('acronym', $this->Value('acronym'));
            $this->di->session->set('id', $user->id);
            $this->di->session->set('email', $user->email);
            if ($user->isAdmin) {
                $this->di->session->set('isAdmin', 1);
            }
            return true;
        } else {
            $this->error .= 'Felaktigt lösenord.';
            return false;
        }
    }
}
