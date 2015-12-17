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

/*
// Scaffolding demo, use anax-scaffold.php to add demo class Scaffold to try it out
$di->set('ScaffoldController', function() use ($di) {
$controller = new \CR\Scaffold\ScaffoldController();
$controller->setDI($di);
return $controller;
});

$app->router->add('scaffold', function() use ($app) {
$app->dispatcher->forward([
'controller' => 'scaffold',
'action'     => 'index',
]);
});
*/

/**
* Start page
*
*/
$app->router->add('', function() use ($app) {
    $app->theme->setTitle("PIGS IN SPACE");

    $app->views->add('default/page', [
        'title' => "<h2>Allt om grisar i rymden</h2>",
        'content' => "<p>Start page</p>",
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
    $app->theme->setTitle("Om WGTOTW");

    $app->views->add('theme/index', [
        'content' => "<h2>Om WGTOTW</h2><p>Här kommer lite info om sidan senare...</p>",
    ], 'main-extended');
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
