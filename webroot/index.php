<?php
/**
* This is a Anax pagecontroller.
*
*/
require __DIR__.'/config_with_app.php';
//require __DIR__.'/config_with_app_WGTOTW.php';

$app->url->setUrlType(\Anax\Url\CUrl::URL_CLEAN);
$app->navbar->configure(ANAX_APP_PATH . 'config/navbar_wgtotw.php');
$app->theme->configure(ANAX_APP_PATH . 'config/theme_wgtotw.php');

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
    $app->theme->setTitle("WGTOTW");
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

    $app->views->add('wgtotw/source', [
        'content' => $source->View(),
    ]);
});

$app->router->handle();
$app->theme->render();
