<?php

namespace CR\HTMLForm;

/**
 * Anax base class for wrapping sessions.
 *
 */
class CFormAddAnswer extends \Mos\HTMLForm\CForm
{
    use \Anax\DI\TInjectionaware,
    \Anax\MVC\TRedirectHelpers;

    private $questionId;
    private $lastID;

    /**
     * Constructor
     *
     */
    public function __construct($questionId = null) {

        $this->questionId = $questionId;

        parent::__construct(['id' => 'answer-form', 'class' => 'answer-form'], [
            'content' => [
            'type'          => 'textarea',
            'label'         => 'Svar (använd Markdown):',
            'required'      => true,
            'validation'    => ['not_empty'],
            ],

            'submit' => [
            'type'      => 'submit',
            'value'     => 'Posta svar',
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
            $this->redirectTo('question/id/'.$this->questionId);
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

        $this->answer = new \CR\Answer\Answer();
        $this->answer->setDI($this->di);
        // Save answer
        $this->answer->save([
            'content'      => strip_tags($this->Value('content')),
            'created'   => $now,
            'upvotes'   => 0,
            'downvotes' => 0,
            'answerUserId'  => $this->di->session->get('id'),
            'questionId'    => $this->questionId,
            ]);
        $this->lastID = $this->answer->db->lastInsertId();

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
        $this->redirectTo('question/id/' . $this->questionId . '#answer-' . $this->lastID);
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
