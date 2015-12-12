<?php
/**
 * Config file for pagecontrollers, creating an instance of $app.
 *
 */

// Get environment & autoloader.
require __DIR__.'/config.php';

// Create services and inject into the app.
$di  = new \CR\DI\CDIFactoryExtended();
$app = new \Anax\Kernel\CAnax($di);

$app->session(); // Will load the session service which also starts the session
