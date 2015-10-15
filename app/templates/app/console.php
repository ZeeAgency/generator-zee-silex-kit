<?php

$app = require __DIR__.'/core.php';

$app['monolog']->popHandler();
$app['monolog']->pushHandler(new Monolog\Handler\RotatingFileHandler(
    $app['monolog.consolelog'],
    $app['monolog.maxfiles'],
    $app['monolog.level'],
    false
));

$console = new Symfony\Component\Console\Application('App CLI');
$basePath = realpath(__DIR__.'/../src');
$classes = array();

$finder = new Symfony\Component\Finder\Finder();
$finder->in($basePath)->files()->name('*Command.php');

/** @var \Symfony\Component\Finder\SplFileInfo $file */
foreach ($finder as $file) {
    $classPath = $file->getRelativePathname();
    $namespace = str_replace(DIRECTORY_SEPARATOR, '\\', $file->getRelativePath());
    $className = $file->getBasename(sprintf('.%s', $file->getExtension()));
    $fullPath = sprintf('\%s\%s', $namespace, $className);

    $classes[] = $fullPath;
}

// Add any manually specified tasks
if (isset($app['config']['commands'])) {
    foreach ($app['config']['commands'] as $command) {
        $classes[] = $command;
    }
}

foreach ($classes as $command) {
    $reflection = new \ReflectionClass($command);

    if ($reflection->isInstantiable()) {
        /** @var \Symfony\Component\Console\Command\Command $instance */
        $instance = $reflection->newInstance();
        $console->add($instance);
    }
}

$helpers = $console->getHelperSet();
$helpers->set(new Zee\Tools\Helper\SilexApplicationHelper($app));
$helpers->set(new Zee\Tools\Helper\LoggerHelper($app['monolog']));

return $console;
