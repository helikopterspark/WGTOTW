<?php

namespace CR\HTMLForm;

/**
 * Anax base class for wrapping sessions.
 *
 */
class CFormAddUser extends \Mos\HTMLForm\CForm
{
    use \Anax\DI\TInjectionaware,
    \Anax\MVC\TRedirectHelpers;

    /**
     * Constructor
     *
     */
    public function __construct()
    {
        parent::__construct([], [
            'acronym' => [
            'type'          => 'text',
            'label'         => 'Användarnamn:',
            'required'      => true,
            'validation'    => ['not_empty'],
            ],
            'name' => [
            'type'        => 'text',
            'label'       => 'Namn:',
            'required'    => true,
            'validation'  => ['not_empty'],
            ],
            'password' => [
            'type'          => 'password',
            'label'         => 'Lösenord',
            'required'      => true,
            'validation'    => ['not_empty'],
            ],
            'email' => [
            'type'        => 'text',
            'label'         => 'Email:',
            'required'    => true,
            'validation'  => ['not_empty', 'email_adress'],
            ],
            'active' => [
            'type'          => 'checkbox',
            'label'         => 'Aktivera',
            'checked'       => true,
            ],
            'submit' => [
            'type'      => 'submit',
            'value'     => 'Registrera',
            'callback'  => [$this, 'callbackSubmit'],
            ],
            'reset' => [
            'type'      => 'reset',
            'value'     => 'Rensa',
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

        $now = date('Y-m-d H:i:s');
        $active = !empty($_POST['active']) ? $now : null;

        $this->user = new \CR\Users\User();
        $this->user->setDI($this->di);

        if (version_compare(phpversion(), '5.5.0', '<')) {
            $enc_password = md5($this->Value('password'));
        } else {
            $enc_password = password_hash($this->Value('password'), PASSWORD_DEFAULT);
        }

        $this->user->save([
            'acronym' => $this->Value('acronym'),
            'email' => $this->Value('email'),
            'name' => $this->Value('name'),
            'password' => $enc_password,
            'created' => $now,
            'active' => $active,
            ]);

        //$this->saveInSession = true;
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
        $this->redirectTo('users/id/' . $this->user->getProperties()['id']);
    }



    /**
     * Callback What to do when form could not be processed?
     *
     */
    public function callbackFail()
    {
        $this->AddOutput("<p><i>Det gick inte att spara. Kontrollera fälten.</i></p>");
        $this->redirectTo();
    }
}
