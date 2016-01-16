<?php
/**
* This is a Anax pagecontroller.
*
*/
//require __DIR__.'/config_with_app.php';
require __DIR__.'/config_with_app_WGTOTW.php';

$app->url->setUrlType(\Anax\Url\CUrl::URL_CLEAN);
$app->navbar->configure(ANAX_APP_PATH . 'config/navbar_wgtotw.php');
$app->theme->configure(ANAX_APP_PATH . 'config/theme-grid.php');

/**
* Start page
*
*/
$app->router->add('', function() use ($app) {
    $app->theme->setTitle("RED PLANET");

    $app->dispatcher->forward([
      'controller' => 'question',
      'action' => 'getlatest',
      'params' => [10],
  ]);

  $app->dispatcher->forward([
    'controller' => 'users',
    'action' => 'getranked',
    'params' => [6],
]);

    $app->dispatcher->forward([
      'controller' => 'tag',
      'action' => 'getmostpopular',
      'params' => [7, null],
  ]);

});

/**
 * Dispatch to QuestionController and list all questions in db
 *
 */
$app->router->add('question', function() use ($app) {
  $app->dispatcher->forward([
    'controller' => 'question',
    'action' => 'index'
    ]);
});

/**
 * Dispatch to TagController and list all tags in db
 *
 */
$app->router->add('tag', function() use ($app) {
  $app->dispatcher->forward([
    'controller' => 'tag',
    'action' => 'index'
    ]);
});

/**
 * Dispatch to UsersController and list all users in db
 *
 */
$app->router->add('users', function() use ($app) {
  $app->dispatcher->forward([
    'controller' => 'users',
    'action' => 'index'
    ]);
});

/**
* About page
*
*/
$app->router->add('about', function() use($app) {
    $content = $app->fileContent->get('about.md');
   $content = $app->textFilter->doFilter($content, 'shortcode, markdown');
    $app->theme->setTitle("Om WGTOTW");

    $app->views->add('theme/index', [
        'content' => $content,
    ], 'main-extended');
});

/**
 * Dispatch to UserLoginController and show login page
 *
 */
$app->router->add('login', function() use ($app) {
    $app->theme->setTitle("Logga in");
  $app->dispatcher->forward([
    'controller' => 'userlogin',
    'action' => 'login'
    ]);
});

/**
 * Dispatch to UserLoginController and logout
 *
 */
$app->router->add('logout', function() use ($app) {
    $app->theme->setTitle("Logga ut");
  $app->dispatcher->forward([
    'controller' => 'userlogin',
    'action' => 'logout'
    ]);
});

/**
* Source code
*
*/
$app->router->add('source', function() use ($app) {
    $app->theme->addStylesheet('css/source.css');
    $app->theme->setTitle("Source code");

    $source = new \Mos\Source\CSource([
        'secure_dir' => '..',
        'base_dir' => '..',
        'add_ignore' => ['.htaccess'],
    ]);

    $app->views->add('theme/index', [
        'content' => $source->View(),
    ], 'fullpage');
});

$app->router->handle();
$app->theme->render();
