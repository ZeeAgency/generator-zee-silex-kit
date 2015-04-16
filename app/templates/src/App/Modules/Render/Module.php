<?php
namespace App\Modules\Render;

use Zee;
use Silex\Application;

class Module extends Zee\Module
{
    public function setup(Application $app)
    {
        parent::setup($app);

        $app['render.controller'] = $this->share(function () use ($app) {
            return new Controller\RenderController($app, $this);
        });
    }

    public function getController(Application $app)
    {
        $controller = parent::getController($app);

        $controller->get('/', 'render.controller:indexAction');
        $controller->get('/hello/{name}/', 'render.controller:helloAction');

        return $controller;
    }
}
