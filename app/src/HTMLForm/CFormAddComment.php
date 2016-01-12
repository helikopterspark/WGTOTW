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

    private $postId;
    private $type;
    private $pageId;

    /**
     * Constructor
     *
     */
    public function __construct($params = null)
    {
        $this->postId = $params['postId'];
        $this->type = $params['type'];
        $this->pageId = $params['pageId'];

        parent::__construct(['id' => 'comment-form', 'class' => 'comment-form'], [
            'content' => [
            'type'          => 'textarea',
            'label'         => 'Kommentar (använd gärna Markdown):',
            'required'      => true,
            'autofocus'     => true,
            'validation'    => ['not_empty'],
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
            $this->redirectTo('question/id/'.$this->pageId.'#comments');
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
            'created'   => $now,
            'upvotes'   => 0,
            'downvotes' => 0,
            'commentUserId'    => $this->di->session->get('id'),
            ]);

        // Save comment2 . $this->type
        $this->lastID = $this->comment->db->lastInsertId();

        $tablename = 'comment2'.$this->type;
        $this->di->db->insert(
            $tablename,
            ['id'.ucfirst($this->type), 'idComment']
        );
        $this->di->db->execute(array($this->postId, $this->lastID));

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

        $this->redirectTo('question/id/'.$this->pageId . '#comment-' . $this->lastID);
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
