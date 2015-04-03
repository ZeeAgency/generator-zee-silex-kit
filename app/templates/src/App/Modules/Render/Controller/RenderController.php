<?php
namespace App\Modules\Render\Controller;

use Silex\Application;
use App\Modules\Render\Module;

class RenderController
{
    private $module;

    public function __construct(Application $app, Module $module)
    {
        $this->module = $module;
    }

    public function indexAction(Application $app)
    {
        return $app['twig']->render('pages/index.twig');
    }

    public function helloAction(Application $app, $name = '')
    {
        return $this->module['twig']->render('page.twig', array(
            'name' => $name,
        ));
    }
}
