<?php
/**
 * Config-file for navigation bar.
 *
 */

 // Check whether user is logged in
 $source = null;
if ($this->di->session->has('acronym')) {
    // show user gravatar and acronym in menu bar
    $gravatar = 'http://www.gravatar.com/avatar/' . md5(strtolower(trim($this->di->session->get('email')))) . '.jpg?s=20&d=identicon';
    // if admin, show source code menu item
    if ($this->di->session->get('isAdmin')) {
        $login = [
            'text'  => '<span class="navbar-img"><img src="'.$gravatar . '" alt="gravatar"></span>&nbsp;' . $this->di->session->get('acronym').'&nbsp;&#9662;',
            'url'   => $this->di->get('url')->create('users/id/'.$this->di->session->get('id')),
            'title' => 'Översikt över användare',
            //'mark-if-parent-of' => 'users',
            'submenu' => [
                'items' => [
                    'source' => [
                        'text'  =>'Source code',
                        'url'   => $this->di->get('url')->create('source'),
                        'title' => 'Source code'
                    ],
                    'logout' => [
                        'text' => 'Logga ut',
                        'url'   => $this->di->get('url')->create('logout'),
                        'title' => 'Logga ut'
                    ],
                ],
            ],
        ];

    } else {

        $login = [
            'text'  => '<span class="navbar-img"><img src="'.$gravatar . '" alt="gravatar" height="20" width="20"></span>&nbsp;' . $this->di->session->get('acronym').'&nbsp;&#9662;',
            'url'   => $this->di->get('url')->create('users/id/'.$this->di->session->get('id')),
            'title' => 'Inloggad som ' . $this->di->session->get('acronym'),
            //'mark-if-parent-of' => 'users',
            'submenu' => [
                'items' => [
                    'logout' => [
                        'text' => 'Logga ut',
                        'url'   => $this->di->get('url')->create('logout'),
                        'title' => 'Logga ut'
                    ],
                ],
            ],
        ];
    }
} else {
    // Not logged in, show Login menu item
    $login = [
        'text'  =>'LOGGA IN',
        'url'   => $this->di->get('url')->create('login'),
        'title' => 'Logga in'
    ];
}

return [

    // Use for styling the menu
    'class' => 'navbar',

    // Here comes the menu strcture
    'items' => [

        // This is a menu item
        'home'  => [
            'text'  => 'HEM',
            'url'   => $this->di->get('url')->create(''),
            'title' => 'Startsidan'
        ],

        // This is a menu item
        'questions'  => [
            'text'  => 'FRÅGOR',
            'url'   => $this->di->get('url')->create('question'),
            'title' => 'Frågor och svar',
            'mark-if-parent-of' => 'question',
        ],

        // This is a menu item
        'tags'  => [
            'text'  => 'ÄMNEN',
            'url'   => $this->di->get('url')->create('tag'),
            'title' => 'Ämnestaggar'
        ],

        // This is a menu item
        'users'  => [
            'text'  => 'ANVÄNDARE',
            'url'   => $this->di->get('url')->create('users'),
            'title' => 'Översikt över användare',
            'mark-if-parent-of' => 'users',
        ],

        // This is a menu item
        'about' => [
            'text'  =>'OM',
            'url'   => $this->di->get('url')->create('about'),
            'title' => 'Om webbplatsen'
        ],

        // This is a menu item
        'ask' => [
            'text'  =>'STÄLL FRÅGA',
            'url'   => $this->di->get('url')->create('question/add'),
            'title' => 'Ställ en fråga'
        ],

        $login,
    ],
/*
        // This is a menu item
        'test'  => [
            'text'  => 'Submenu',
            'url'   => $this->di->get('url')->create('submenu'),
            'title' => 'Submenu with url as internal route within this frontcontroller',

            // Here we add the submenu, with some menu items, as part of a existing menu item
            'submenu' => [

                'items' => [

                    // This is a menu item of the submenu
                    'item 0'  => [
                        'text'  => 'Item 0',
                        'url'   => $this->di->get('url')->create('submenu/item-0'),
                        'title' => 'Url as internal route within this frontcontroller'
                    ],

                    // This is a menu item of the submenu
                    'item 2'  => [
                        'text'  => '/humans.txt',
                        'url'   => $this->di->get('url')->asset('/humans.txt'),
                        'title' => 'Url to sitespecific asset',
                        'class' => 'italic'
                    ],

                    // This is a menu item of the submenu
                    'item 3'  => [
                        'text'  => 'humans.txt',
                        'url'   => $this->di->get('url')->asset('humans.txt'),
                        'title' => 'Url to asset relative to frontcontroller',
                    ],
                ],
            ],
        ],

        // This is a menu item
        'controller' => [
            'text'  =>'Controller (marked for all descendent actions)',
            'url'   => $this->di->get('url')->create('controller'),
            'title' => 'Url to relative frontcontroller, other file',
            'mark-if-parent-of' => 'controller',
        ],
*/


    /**
     * Callback tracing the current selected menu item base on scriptname
     *
     */
    'callback' => function ($url) {
        if ($url == $this->di->get('request')->getCurrentUrl(false)) {
            return true;
        }
    },



    /**
     * Callback to check if current page is a decendant of the menuitem, this check applies for those
     * menuitems that has the setting 'mark-if-parent' set to true.
     *
     */
    'is_parent' => function ($parent) {
        $route = $this->di->get('request')->getRoute();
        return !substr_compare($parent, $route, 0, strlen($parent));
    },



   /**
     * Callback to create the url, if needed, else comment out.
     *
     */
   /*
    'create_url' => function ($url) {
        return $this->di->get('url')->create($url);
    },
    */
];
