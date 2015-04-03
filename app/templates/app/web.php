<?php
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;

$app = require __DIR__.'/core.php';

/** @var Twig_Environment $twig */
$twig = $app['twig'];

/* Error catcher */
$app->error(function (\Exception $e, $code) use ($app, $twig) {
    if ($app['debug']) {
        // return;
    }

    /** @var Request $request */
    $request = $app['request'];

    if (strpos($request->headers->get('Content-Type'), 'application/json') === 0) {
        return new JsonResponse(array(
            'statusCode' => $code,
            'message' => $e->getMessage(),
            // 'stacktrace' => $e->getTraceAsString(), // Verbose!
        ));
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

    return new Response($response, $code);
});

return $app;
