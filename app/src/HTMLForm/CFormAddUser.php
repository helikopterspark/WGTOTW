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
    public function __construct($tempuser = null)
    {
        parent::__construct(['id' => 'user-form', 'class' => 'user-form'], [
            'acronym' => [
            'type'          => 'text',
            'label'         => 'Användarnamn:',
            'required'      => true,
            'autofocus'     => true,
            'validation'    => ['not_empty'],
            'value'         => $tempuser['acronym'] ? $tempuser['acronym'] : null,
            ],
            'name' => [
            'type'        => 'text',
            'label'       => 'Namn:',
            'required'    => true,
            'validation'  => ['not_empty'],
            'value'         => $tempuser['name'] ? $tempuser['name'] : null,
            ],
            'email' => [
            'type'        => 'text',
            'label'         => 'Email:',
            'required'    => true,
            'validation'  => ['not_empty', 'email_adress'],
            'value'         => $tempuser['email'] ? $tempuser['email'] : null,
            ],
            'url' => [
            'type'          => 'url',
            'label'         => 'URL',
            'required'      => false,
            'value'         => $tempuser['url'] ? $tempuser['url'] : null,
            ],
            'password' => [
            'type'          => 'password',
            'label'         => 'Lösenord',
            'required'      => true,
            'validation'    => ['not_empty'],
            ],
            'repeat_password' => [
            'type'          => 'password',
            'label'         => 'Upprepa lösenord',
            'required'      => true,
            'validation'    => ['not_empty'],
            ],
            'colortheme' => [
                'type'      => 'radio',
                'label'     => 'Färgtema:',
                'required'  => false,
                'values'   => [
                    'light-theme' => 'light-theme',
                    'dark-theme' => 'dark-theme',
                ],
                'checked'   => $tempuser['colortheme'] ? $tempuser['colortheme'] : 'light-theme',
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

        if ($this->di->request->getPost('submit-abort')) {
            $this->di->session->set('tempuser', null);  // clear tempuser info
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

        // Save temporary info in session in case registration validation fails
        $tempuser = array('acronym' => $this->Value('acronym'),
            'email' => $this->Value('email'),
            'name' => $this->Value('name'),
            'url' => $this->Value('url'),
            'colortheme' => $this->Value('colortheme'),
        );

        // Check whether acronym already exists
        $userExists = $this->user->query()
            ->where('acronym = ?')
            ->execute([$this->Value('acronym')]);

        if ($userExists) {
            $this->di->session->set('tempuser', $tempuser);
            $this->di->flashmessage->alert('<p><span class="flashmsgicon"><i class="fa fa-exclamation-circle fa-2x"></i></span>&nbsp;Användarnamnet '.$this->Value('acronym').' är upptaget!</p>');
            $this->redirectTo('users/add');
        }

        // Check whether email address already exists
        $emailExists = $this->user->query()
            ->where('email = ?')
            ->execute([$this->Value('email')]);

        if ($emailExists) {
            $this->di->session->set('tempuser', $tempuser);
            $this->di->flashmessage->alert('<p><span class="flashmsgicon"><i class="fa fa-exclamation-circle fa-2x"></i></span>&nbsp;Det finns redan en användare med mailadressen '.$this->Value('email').' registrerad!</p>');
            $this->redirectTo('users/add');
        }

        // Check whether passwords match
        if ($this->Value('password') !== $this->Value('repeat_password')) {
            $this->di->session->set('tempuser', $tempuser);
            $this->di->flashmessage->alert('<p><span class="flashmsgicon"><i class="fa fa-exclamation-circle fa-2x"></i></span>&nbsp;Lösenorden matchar inte.</p>');
            $this->redirectTo('users/add');
        }

        $now = date('Y-m-d H:i:s');

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
            'url' => $this->Value('url'),
            'colortheme' => $this->Value('colortheme'),
            'created' => $now,
            'active' => $now,
            ]);

        $this->di->session->set('tempuser', null);  // clear tempuser info
        $this->di->flashmessage->success('<p><span class="flashmsgicon"><i class="fa fa-check-circle fa-2x"></i></span>&nbsp;Användaren '.$this->Value('acronym').' är registrerad!</p>');
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
