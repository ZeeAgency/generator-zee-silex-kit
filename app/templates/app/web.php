<?php

$app = require __DIR__.'/core.php';

/** @var Twig_Environment $twig */
$twig = $app['twig'];

/* Error catcher */
$app->error(function (\Exception $e, $code) use ($app, $twig) {
    if ($app['debug']) {
        return;
    }

    $message = '';

    switch ($code) {
        case 404:
            $template = 'errors/404.twig';
            break;

        default:
            $message = $e->getMessage();
            $template = 'errors/misc.twig';
    }

    $response = $twig->render($template, array(
        'message' => $message,
    ));

    return new Symfony\Component\HttpFoundation\Response($response, $code);
});

return $app;
