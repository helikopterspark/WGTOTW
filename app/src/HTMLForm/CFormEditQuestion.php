<?php

namespace CR\HTMLForm;

/**
 * Anax base class for wrapping sessions.
 *
 */
class CFormEditQuestion extends \Mos\HTMLForm\CForm
{
    use \Anax\DI\TInjectionaware,
    \Anax\MVC\TRedirectHelpers;

    private $error;
    private $questionUpd;
    private $tags;
    private $lastID;

    /**
     * Constructor
     *
     */
    public function __construct($question, $tags = null)
    {
        $this->error = '<span class="flashmsgicon"><i class="fa fa-times-circle fa-2x"></i></span>&nbsp;';
        $this->questionUpd = $question;

        // Preselected tags
        foreach ($question->getProperties()['tags'] as $pre) {
            $preselected[] = $pre->getProperties()['id'];
        }

        $this->tags = $tags;
        foreach ($this->tags as $value) {
            $tagTitles[$value->getProperties()['id']] = $value->getProperties()['name'];
        }

        parent::__construct(['id' => 'question-editform', 'class' => 'question-editform'], [
            'title' => [
            'type'          => 'text',
            'label'         => 'Titel:',
            'required'      => true,
            'autofocus'     => true,
            'validation'    => ['not_empty'],
            'value'         => $question->getProperties()['title'],
            ],
            'content' => [
            'type'          => 'textarea',
            'label'         => 'Fråga (använd Markdown):',
            'required'      => true,
            'validation'    => ['not_empty'],
            'value'         => $question->getProperties()['content'],
            ],
            'tags' => [
            'type'          => 'select-multiple',
            'options'       => $tagTitles,
            'checked'      => $preselected,
            'label'         => 'Ämnestaggar (flera val möjliga)',
            'required'       => true,
            'size'          => 10,
            ],

            'submit' => [
            'type'      => 'submit',
            'value'     => 'Uppdatera fråga',
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
            $this->redirectTo('question/id/'. $this->questionUpd->getProperties()['id']);
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

        $this->question = new \CR\Question\Question();
        $this->question->setDI($this->di);
        // Save question
        $this->question->save([
            'id' => $this->questionUpd->getProperties()['id'],
            'title'      => strip_tags($this->Value('title')),
            'content'      => strip_tags($this->Value('content')),
            'updated'   => $now,
            //'questionUserId'  => $this->di->session->get('id')
            ]);

        // Save tag2question
        $selectedTags = $this->di->request->getPost('tags');
        // Clear previous entries
        $this->di->db->delete(
            'tag2question',
            'idQuestion = ?'
        );
        $this->di->db->execute([$this->questionUpd->getProperties()['id']]);
        // Save new entries
        $this->di->db->insert(
            'tag2question',
            ['idQuestion', 'idTag']
        );
        foreach ($selectedTags as $tagID) {
            $this->di->db->execute(array($this->questionUpd->getProperties()['id'], $tagID));
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
        $this->redirectTo('question/id/' . $this->questionUpd->getProperties()['id']);
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
