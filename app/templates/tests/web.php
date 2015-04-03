<?php
$app = require __DIR__.'/../app/web.php';

$app['env'] = 'test';
$app['session.test'] = true;

unset($app['exception_handler']);

$app['monolog']->popHandler();
$app['monolog']->pushHandler(new Monolog\Handler\RotatingFileHandler(
    $app['monolog.testlog'],
    $app['monolog.maxfiles'],
    $app['monolog.level'],
    false
));

return $app;
