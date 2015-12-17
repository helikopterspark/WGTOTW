<?php

namespace CR\HTMLForm;

/**
 * Anax base class for wrapping sessions.
 *
 */
class CFormAddComment extends \Mos\HTMLForm\CForm
{
    use \Anax\DI\TInjectionaware,
    \Anax\MVC\TRedirectHelpers;

    private $page;
    private $redirect;

    /**
     * Constructor
     *
     */
    public function __construct($params = null)
    {
        parent::__construct([], [
            'content' => [
            'type'          => 'textarea',
            'label'         => 'Kommentar (använd gärna Markdown):',
            'required'      => true,
            'validation'    => ['not_empty'],
            ],
            'name' => [
            'type'        => 'text',
            'label'       => 'Namn:',
            'required'    => true,
            'validation'  => ['not_empty'],
            ],
            'email' => [
            'type'        => 'text',
            'label'         => 'Email:',
            'required'    => true,
            'validation'  => ['not_empty', 'email_adress'],
            ],
            'url' => [
            'type'      => 'url',
            'label'     => 'Hemsida:',
            'required'  => false,
            ],
            'submit' => [
            'type'      => 'submit',
            'value'     => 'Spara',
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

        $this->page = $params['id'];
        $this->redirect = $params['redirect'];
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
            $this->redirectTo($this->redirect.'/id/'.$this->page.'#comments');
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

        $this->comment = new \CR\Comment\Comment();
        $this->comment->setDI($this->di);

        $this->comment->save([
            'content'   => strip_tags($this->Value('content')),
            'name'      => $this->Value('name'),
            'email'     => $this->Value('email'),
            'url'       => $this->Value('url'),
            'ip'        => $this->di->request->getServer('REMOTE_ADDR'),
            'created'   => $now,
            'redirect'  => $this->redirect,
            'page'      => $this->page
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
        $this->comment = new \CR\Comment\Comment();
        $this->comment->setDI($this->di);

        $this->redirectTo($this->redirect .'#comment-' . $this->comment->db->lastInsertId());
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
