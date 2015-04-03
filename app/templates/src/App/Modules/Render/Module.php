<?php
namespace App\Modules\Render;

use Zee;
use Silex\Application;

class Module extends Zee\Module
{
    public function setup(Application $app)
    {
        parent::setup($app);
        $module = $this;

        $app['render.controller'] = $this->share(function () use ($app, $module) {
            return new Controller\RenderController($app, $module);
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
