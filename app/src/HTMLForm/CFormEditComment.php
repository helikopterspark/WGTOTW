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

    private $type;
    private $pageId;

    /**
     * Constructor
     *
     */
    public function __construct($params = null)
    {

        $this->commentUpd = $params['comment'];
        $this->type = $params['type'];
        $this->pageId = $params['pageId'];

        parent::__construct(['id' => 'comment-form', 'class' => 'comment-form'], [
            'content' => [
            'type'          => 'textarea',
            'label'         => 'Kommentar (använd gärna Markdown):',
            'required'      => true,
            'autofocus'     => true,
            'validation'    => ['not_empty'],
            'value'         => $this->commentUpd->getProperties()['content'],
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
            $this->redirectTo('question/id/'.$this->pageId.'#comment-'.$this->commentUpd->getProperties()['id']);
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
            'updated'   => $now,
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
        $this->redirectTo('question/id/'.$this->pageId.'#comment-'.$this->commentUpd->getProperties()['id']);
    }

    /**
     * Callback What to do if the form was submitted?
     * Delete comment. NOTE: soft delete
     *
     */
    public function callbackDelete()
    {
        $now = date('Y-m-d H:i:s');

        $this->comment = new \CR\Comment\Comment();
        $this->comment->setDI($this->di);

        $this->comment->save([
            'id'        => $this->commentUpd->getProperties()['id'],
            'deleted'   => $now,
            ]);

        $this->di->flashmessage->info('<span class="flashmsgicon"><i class="fa fa-info-circle fa-2x"></i></span>&nbsp;Kommentaren togs bort.');
        return true;

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
