<?php

namespace CR\HTMLForm;

/**
 * Anax base class for wrapping sessions.
 *
 */
class CFormEditComment extends \Mos\HTMLForm\CForm
{
    use \Anax\DI\TInjectionaware,
    \Anax\MVC\TRedirectHelpers;

    private $page;
    private $redirect;

    /**
     * Constructor
     *
     */
    public function __construct($comment = null, $params = null)
    {
        parent::__construct([], [
            'content' => [
            'type'          => 'textarea',
            'label'         => 'Kommentar (använd gärna Markdown):',
            'required'      => true,
            'validation'    => ['not_empty'],
            'value'         => $comment->getProperties()['content'],
            ],
            'name' => [
            'type'        => 'text',
            'label'       => 'Namn:',
            'required'    => true,
            'validation'  => ['not_empty'],
            'value'         => $comment->getProperties()['name'],
            ],
            'email' => [
            'type'        => 'text',
            'label'         => 'Email:',
            'required'    => true,
            'validation'  => ['not_empty', 'email_adress'],
            'value'         => $comment->getProperties()['email'],
            ],
            'url' => [
            'type'      => 'url',
            'label'     => 'Hemsida:',
            'required'  => false,
            'value'         => $comment->getProperties()['url'],
            ],
            'submit' => [
            'type'      => 'submit',
            'value'     => 'Spara',
            'callback'  => [$this, 'callbackSubmit'],
            ],
            'reset' => [
            'type'      => 'reset',
            'value'     => 'Återställ',
            ],
            'delete' => [
            'type'      => 'submit',
            'value'     => 'Radera',
            'callback'  => [$this, 'callbackDelete'],
            ],
            'submit-abort' => [
            'type'      => 'submit',
            'value'     => 'Avbryt',
            'formnovalidate' => true,
            'callback'  => [$this, 'callbackSubmitFail'],
            ],

            ]);
        $this->commentUpd = $comment;
        $this->page = $params['page'];
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
        if ($this->di->request->getPost('submit-abort')) {
            $this->redirectTo($this->redirect.'#comments');
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
            'id'        => $this->commentUpd->getProperties()['id'],
            'content'   => strip_tags($this->Value('content')),
            'name'      => $this->Value('name'),
            'email'     => $this->Value('email'),
            'url'       => $this->Value('url'),
            'ip'        => $this->di->request->getServer('REMOTE_ADDR'),
            'updated'   => $now,
            'redirect'  => $this->redirect,
            'page'      => $this->page
            ]);

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
        $this->redirectTo($this->redirect.'#comment-'.$this->commentUpd->getProperties()['id']);
    }

    /**
     * Callback What to do if the form was submitted?
     *
     */
    public function callbackDelete()
    {
        $id = $this->commentUpd->getProperties()['id'];
        $deleted = $this->commentUpd->delete($id);

        if ($deleted) {
            return true;
        } else {
            return false;
        }
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
