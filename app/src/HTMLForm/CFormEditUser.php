<?php

namespace CR\HTMLForm;

/**
 * Anax base class for wrapping sessions.
 *
 */
class CFormEditUser extends \Mos\HTMLForm\CForm
{
    use \Anax\DI\TInjectionaware,
    \Anax\MVC\TRedirectHelpers;

    /**
     * Constructor
     *
     */
    public function __construct($user = null)
    {
        $this->userUpd = $user;

        $active = false;

        if ($user->getProperties()['active']) {
            $active = true;
        }

        parent::__construct([], [
            'acronym' => [
            'type'          => 'text',
            'label'         => 'Användarnamn:',
            'required'      => true,
            'validation'    => ['not_empty'],
            'value'         => $user->getProperties()['acronym'],
            ],
            'name' => [
            'type'        => 'text',
            'label'       => 'Namn:',
            'required'    => true,
            'validation'  => ['not_empty'],
            'value'         => $user->getProperties()['name'],
            ],
            'password' => [
            'type'          => 'password',
            'label'         => 'Lösenord',
            'required'      => false,
            //'validation'    => ['not_empty'],
            //'value'         => $user->getProperties()['password'],
            ],
            'email' => [
            'type'        => 'text',
            'label'         => 'Email:',
            'required'    => true,
            'validation'  => ['not_empty', 'email_adress'],
            'value'         => $user->getProperties()['email'],
            ],
            'active' => [
            'type'          => 'checkbox',
            'label'         => 'Aktivera',
            'checked'       => $active,
            ],
            'submit' => [
            'type'      => 'submit',
            'value'     => 'Uppdatera',
            'callback'  => [$this, 'callbackSubmit'],
            ],
            'reset' => [
            'type'      => 'reset',
            'value'     => 'Återställ',
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
        $deleted = $this->userUpd->getProperties()['deleted'];

        if ($this->userUpd->getProperties()['active'] && !empty($_POST['active'])) {
            $active = $this->userUpd->getProperties()['active'];
        } else {
            $active = !empty($_POST['active']) ? $now : null;
            if ($active) {
                $deleted = null;
            }
        }

        $password = $this->Value('password');

        if ($password) {
            if (version_compare(phpversion(), '5.5.0', '<')) {
                $enc_password = md5($password);
            } else {
                $enc_password = password_hash($password, PASSWORD_DEFAULT);
            }
        } else {
            $enc_password = $this->userUpd->getProperties()['password'];
        }



        $this->userUpd->save([
            'id' => $this->userUpd->getProperties()['id'],
            'acronym' => $this->Value('acronym'),
            'email' => $this->Value('email'),
            'name' => $this->Value('name'),
            'password' => $enc_password,
            'updated' => $now,
            'active' => $active,
            'deleted' => $deleted
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
        $this->redirectTo('users/id/' . $this->userUpd->getProperties()['id']);
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
