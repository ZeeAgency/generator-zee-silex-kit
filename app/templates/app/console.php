<?php
use Symfony\Component\Console\Application;

$app = require __DIR__.'/core.php';

$app['monolog']->popHandler();
$app['monolog']->pushHandler(new Monolog\Handler\RotatingFileHandler(
    $app['monolog.consolelog'],
    $app['monolog.maxfiles'],
    $app['monolog.level'],
    false
));

$console = new Application('App CLI');
$classNames = array();
$tasks = array();

// Add all tasks in the Tasks directory
foreach (glob(ROOT_PATH.'/src/App/Task/*.php') as $classPath) {
    $parts = pathinfo($classPath);
    array_push($classNames, '\App\Task\\'.$parts['filename']);
}

// Add any manually specified tasks
if (isset($app['config']['tasks'])) {
    foreach ($app['config']['tasks'] as $className) {
        array_push($classNames, $className);
    }
}

foreach ($classNames as $class) {
    $task = new $class($app);
    if ($task instanceof Zee\Task) {
        $tasks[$class] = $console->register($task->getName());
        $tasks[$class]->setDefinition($task->getDefinition());
        $tasks[$class]->setDescription($task->getDescription());
        $tasks[$class]->setHelp($task->getHelp());
        $tasks[$class]->setCode($task->getCode());
    }
}

return $console;
