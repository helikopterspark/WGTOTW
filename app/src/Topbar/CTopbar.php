<?php

namespace CR\Topbar;

/**
* Helper to create a navbar for sites by reading its configuration from file
* and then applying some code while rendering the resultning navbar.
*
*/
class CTopbar
{
    use \Anax\TConfigure,
    \Anax\DI\TInjectionAware;


    /**
    * Create the topbar
    *
    * @return string with html
    */
    public function create() {

        if ($this->di->session->has('acronym')) {
            // show user gravatar and acronym in topbar
            $gravatar = 'http://www.gravatar.com/avatar/' . md5(strtolower(trim($this->di->session->get('email')))) . '.jpg?s=20&d=identicon';
            $url = $this->di->get('url')->create('users/id/'.$this->di->session->get('id'));
            $acronym = $this->di->session->get('acronym');

            $login = '<span class="navbar-img"><img src="'.$gravatar . '" alt="Inloggad som '
            . $this->di->session->get('acronym').'" height="20" width="20"></span>&nbsp;'
            . '<a href="'.$url.'" title="Inloggad som '.$acronym.'">'.$acronym.'</a><div class="topbar-spacer"></div>';

            $url = $this->di->get('url')->create('logout');
            $login .= '<i class="fa fa-sign-out"></i><a href="'.$url.'"> LOGGA UT</a><div class="topbar-spacer"></div>';

        } else {
            // Not logged in, show Login link
            $url = $this->di->get('url')->create('users/add');
            $login = '<i class="fa fa-user-plus"></i><a href="'.$url.'"> Bli medlem </a><div class="topbar-spacer"></div>';
            $url = $this->di->get('url')->create('login');
            $login .= '<i class="fa fa-sign-in"></i><a href="'.$url.'"> LOGGA IN</a><div class="topbar-spacer"></div>';
        }

/*
        $form = new \CR\HTMLForm\CFormSearch();
        $form->setDI($this->di);
        $form->check();
        $search = $form->getHTML();
        */
        $search = <<<EOD
            <form id="search-form" class="search-form" action="question/search/" method="post">

                <input id="form-element-search" class="form-element-search" type="text" name="search" placeholder="&#128269; Sök i frågor">

            </form>
EOD;

        $html = <<<EOD
        <div class="header_container">
            <div class="header right-align">
                <div class="inline-div">
                    {$login}
                </div>
                <div class="inline-div">
                    {$search}
                </div>
            </div>
        </div>
EOD;

        return $html;
    }
}
