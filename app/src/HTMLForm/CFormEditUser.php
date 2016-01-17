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

        parent::__construct(['id' => 'user-editform', 'class' => 'user-editform'], [
            'acronym' => [
            'type'          => 'text',
            'label'         => 'Användarnamn:',
            'required'      => true,
            'autofocus'     => true,
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
            'email' => [
            'type'        => 'text',
            'label'         => 'Email:',
            'required'    => true,
            'validation'  => ['not_empty', 'email_adress'],
            'value'         => $user->getProperties()['email'],
            ],
            'url' => [
            'type'          => 'url',
            'label'         => 'URL:',
            'required'      => false,
            'value'         => $user->getProperties()['url'],
            ],
            'password' => [
            'type'          => 'password',
            'label'         => 'Lösenord:',
            'required'      => false,
            ],
            'repeat_password' => [
            'type'          => 'password',
            'label'         => 'Upprepa lösenord',
            'required'      => false,
            ],
            'colortheme' => [
                'type'      => 'radio',
                'label'     => 'Färgtema:',
                'required'  => false,
                'values'   => [
                    'light-theme' => 'light-theme',
                    'dark-theme' => 'dark-theme',
                ],
                'checked'   => $user->getProperties()['colortheme'],
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
        if ($this->di->request->getPost('submit-abort')) {
            $this->redirectTo('users/id/'.$this->userUpd->getProperties()['id']);
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

        // Check whether acronym already exists for another user
        $userExists = $this->userUpd->query()
            ->where('acronym = ?')
            ->andWhere('id != ?')
            ->execute([$this->Value('acronym'), $this->userUpd->getProperties()['id']]);

        if ($userExists) {
            $this->di->flashmessage->alert('<p><span class="flashmsgicon"><i class="fa fa-exclamation-circle fa-2x"></i></span>&nbsp;Användarnamnet '.$this->Value('acronym').' är upptaget!</p>');
            $this->redirectTo('users/update/'.$this->userUpd->getProperties()['id']);
        }

        // Check whether email address already exists
        $emailExists = $this->userUpd->query()
            ->where('email = ?')
            ->andWhere('id != ?')
            ->execute([$this->Value('email'), $this->userUpd->getProperties()['id']]);

        if ($emailExists) {
            $this->di->flashmessage->alert('<p><span class="flashmsgicon"><i class="fa fa-exclamation-circle fa-2x"></i></span>&nbsp;Det finns redan en användare med mailadressen '.$this->Value('email').' registrerad!</p>');
            $this->redirectTo('users/update/'.$this->userUpd->getProperties()['id']);
        }

        // Check whether password is too short
        if ($this->Value('password') && strlen($this->Value('password')) < 4) {
            $this->di->flashmessage->alert('<p><span class="flashmsgicon"><i class="fa fa-exclamation-circle fa-2x"></i></span>&nbsp;Lösenordet är för kort (minst 4 tecken).</p>');
            $this->redirectTo('users/update/'.$this->userUpd->getProperties()['id']);
        }

        // Check whether passwords match
        if ($this->Value('password') !== $this->Value('repeat_password')) {
            $this->di->flashmessage->alert('<p><span class="flashmsgicon"><i class="fa fa-exclamation-circle fa-2x"></i></span>&nbsp;Lösenorden matchar inte.</p>');
            $this->redirectTo('users/update/'.$this->userUpd->getProperties()['id']);
        }

        $now = date('Y-m-d H:i:s');
        $deleted = $this->userUpd->getProperties()['deleted'];

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
            'url' => $this->Value('url'),
            'colortheme' => $this->Value('colortheme'),
            'updated' => $now,
            'active' => $now,
            'deleted' => $deleted
            ]);

        if ($this->di->session->get('id') == $this->userUpd->getProperties()['id']) {
            $this->di->session->set('acronym', $this->Value('acronym'));
            $this->di->session->set('email', $this->Value('email'));
            $this->di->session->set('colortheme', $this->Value('colortheme'));
        }

        $this->di->flashmessage->success('<p><span class="flashmsgicon"><i class="fa fa-check-circle fa-2x"></i></span>&nbsp;Användaren '.$this->Value('acronym').' uppdaterades!</p>');
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
