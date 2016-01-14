<?php

namespace CR\HTMLForm;

/**
 * CForm class for adding tag.
 *
 */
class CFormAddTag extends \Mos\HTMLForm\CForm
{
    use \Anax\DI\TInjectionaware,
    \Anax\MVC\TRedirectHelpers;

    private $lastID;

    /**
     * Constructor
     *
     */
    public function __construct() {

        parent::__construct(['id' => 'tag-form', 'class' => 'tag-form'], [
            'name' => [
            'type'          => 'text',
            'label'         => 'Ämne:',
            'required'      => true,
            'autofocus'     => true,
            'validation'    => ['not_empty'],
            ],
            'description' => [
            'type'          => 'textarea',
            'label'         => 'Beskrivning (max 255 tecken):',
            'required'      => true,
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
            $this->redirectTo('tag');
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

        $this->tag = new \CR\Tag\Tag();
        $this->tag->setDI($this->di);
        // Save question
        $this->tag->save([
            'name'      => strip_tags($this->Value('name')),
            'description'      => strip_tags($this->Value('description')),
            'created'   => $now
            ]);
        $this->lastID = $this->tag->db->lastInsertId();

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
        $this->redirectTo('question/tag/' . $this->lastID);
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
