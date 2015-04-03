<?php
$autoload = require __DIR__.'/../vendor/autoload.php';
$app = new Silex\Application();

/**
 * Environment
 */
$app->register(new Zee\Provider\EnvironmentServiceProvider());

/**
 * Doctrine DBAL
 */
$app->register(new Silex\Provider\DoctrineServiceProvider(), array(
    'dbs.options' => $app['config']['dbs'],
));

/**
 * Linkify & Urlify
 */
$app->register(new Silex\Provider\UrlGeneratorServiceProvider());

/**
 * Symfony2 Components
 */
$app->register(new Silex\Provider\ValidatorServiceProvider());
$app->register(new Silex\Provider\ServiceControllerServiceProvider());

/**
 * Twig
 */
$app->register(new Silex\Provider\TwigServiceProvider(), array(
    'twig.path' => ROOT_PATH.'/public/views',
    'twig.options' => $app['config']['twig'],
));

/**
 * Twig Extensions
 */
$app['twig'] = $app->share($app->extend('twig', function (Twig_Environment $twig, Silex\Application $app) {
    $twig->addGlobal('assets_dir', $app['config']['assets_dir']);
    $twig->addExtension(new Twig_Extension_Debug());
    $twig->addExtension(new Zee\Extensions\Twig\TwigCoreExtension());
    $twig->addExtension(new Zee\Extensions\Twig\TwigMathExtension());

    return $twig;
}));

/**
 * Monolog
 */
$app->register(new Silex\Provider\MonologServiceProvider(), array(
    'monolog.name' => $app['config']['log_name'],
    'monolog.logfile' => $app['config']['logs_dir'].'/'.$app['env'].'.log',
    'monolog.consolelog' => $app['config']['logs_dir'].'/'.$app['env'].'-console.log',
    'monolog.testlog' => $app['config']['logs_dir'].'/'.$app['env'].'-test.log',
    'monolog.consoletestlog' => $app['config']['logs_dir'].'/'.$app['env'].'-consoletest.log',
    'monolog.maxfiles' => 10,
    'monolog.level' => function () use ($app) {
        if ($app['debug'] || !defined('Monolog\Logger::'.strtoupper($app['config']['log_level']))) {
            return Monolog\Logger::DEBUG;
        }

        return constant('Monolog\Logger::'.strtoupper($app['config']['log_level']));
    },
));

$handler = new Monolog\Handler\RotatingFileHandler(
    $app['monolog.logfile'],
    $app['monolog.maxfiles'],
    $app['monolog.level'],
    false
);
$handler->pushProcessor(new Monolog\Processor\WebProcessor());

$app['monolog']->popHandler();
$app['monolog']->pushHandler($handler);

/**
 * SwiftMailer
 */
$app->register(new Silex\Provider\SwiftmailerServiceProvider(), array(
    'swiftmailer.options' => $app['config']['mailer']['smtp'],
));

// Must be loaded before Modules...
$app['orm.mappings'] = $app->share(function () {
    return new ArrayObject();
});

/* Modules */
$app->register(new App\Modules\Render\Module(array('mount' => '/')));
/**/

/**
 * Doctrine Logger
 */
$app->register(new Zee\Provider\DoctrineLoggerServiceProvider());

/**
 * Doctrine ORM
 */
$app->register(new Dflydev\Silex\Provider\DoctrineOrm\DoctrineOrmServiceProvider(), array(
    'orm.proxies_dir' => $app['config']['cache_dir'],
    'orm.em.options' => array(
        'mappings' => $app['orm.mappings']->getArrayCopy(),
    ),
));

\Doctrine\Common\Annotations\AnnotationRegistry::registerLoader(array($autoload, 'loadClass'));

return $app;
