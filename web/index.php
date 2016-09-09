<?php

require('../vendor/autoload.php');

$app = new Silex\Application();
$app['debug'] = true;

// Register the monolog logging service
$app->register(new Silex\Provider\MonologServiceProvider(), array(
  'monolog.logfile' => 'php://stderr',
));

// Register view rendering
$app->register(new Silex\Provider\TwigServiceProvider(), array(
    'twig.path' => __DIR__.'/views',
));

// Our web handlers

$app->get('/', function() use($app) {
  $app['monolog']->addDebug('logging output.');
  return $app['twig']->render('index.twig');
});

$app->run();

// start rollbar 

// installs global error and exception handlers
Rollbar::init(array('access_token' => '0f1fce19bb7b459a8bf1635e6f3b079b'));

// Message at level 'info'
Rollbar::report_message('testing 123', 'info');

// Catch an exception and send it to Rollbar
try {
    throw new Exception('test exception');
} catch (Exception $e) {
    Rollbar::report_exception($e);
}

// Will also be reported by the exception handler
throw new Exception('test 2');

// end rollbar 
