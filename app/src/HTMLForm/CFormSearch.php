<?php

namespace CR\HTMLForm;

/**
 * Anax base class for wrapping sessions.
 *
 */
class CFormSearch extends \Mos\HTMLForm\CForm
{
    use \Anax\DI\TInjectionaware,
    \Anax\MVC\TRedirectHelpers;

    private $error;

    /**
     * Constructor
     *
     */
    public function __construct()
    {
        $this->error = '<span class="flashmsgicon"><i class="fa fa-times-circle fa-2x"></i></span>&nbsp;';

        parent::__construct(['id' => 'search-form', 'class' => 'search-form'], [
            'search' => [
            'type'          => 'search',
            'placeholder'     => '&#128269; Sök i frågor',
            'callback'  => [$this, 'callbackSubmit'],
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
        return parent::check([$this, 'callbackSuccess'], [$this, 'callbackFail']);
    }



    /**
     * Callback for submit-button.
     *
     */
    public function callbackSubmit()
    {
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
        $this->redirectTo('question/search/?search=' . strip_tags($this->Value('search')));
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
