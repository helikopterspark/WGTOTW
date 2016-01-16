<?php
/**
 * Config-file for navigation bar.
 *
 */

 // Check whether user is logged in
 $source = null;
 $login = null;

 $items = [

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
     'ask' => [
         'text'  =>'STÄLL FRÅGA',
         'url'   => $this->di->get('url')->create('question/add'),
         'title' => 'Ställ en fråga'
     ],

 ];

    // if admin, show source code menu item
    if ($this->di->session->get('isAdmin')) {
        $items['admin'] = [
            'text'  => 'ADMIN &#9662;',
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
                    'addtag' => [
                        'text'  => 'Nytt ämne',
                        'url'   => $this->di->get('url')->create('tag/add'),
                        'title' => 'Nytt ämne',
                    ],
                ],
            ],
        ];

    }

return [

    // Use for styling the menu
    'class' => 'navbar',
    // Menu structure comes here
    'items' => $items,


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
