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

        parent::__construct([], [
            'acronym' => [
            'type'          => 'text',
            'label'         => 'Användarnamn:',
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
        if (isset($_POST['submit-abort'])) {
            $this->redirectTo('users');
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
            $this->error .= $login[0]->password;
            $verifyPW = password_verify($this->Value('password'), $login[0]->password);
            if ($verifyPW) {
                $this->id = $login[0]->id;
                $this->di->session->set('acronym', $this->Value('acronym'));
                $this->di->session->set('id', $login[0]->id);
                $this->di->session->set('email', $login[0]->email);
            } else {
                $this->error .= 'Felaktigt lösenord. '.$this->Value('password');
                return false;
            }
        } else {
            $this->error .= 'Användarnamnet finns inte.';
            return false;
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
}
