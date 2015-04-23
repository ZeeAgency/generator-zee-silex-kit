<?php
namespace App\Modules\Render\Controller;

use Silex\Application;
use App\Modules\Render\Module;

class RenderController
{
    private $module;

    /** @var \Twig_Environment $twig */
    private $twig;

    public function __construct(Application $app, Module $module)
    {
        $this->module = $module;
        $this->twig = $module['twig'];
    }

    public function indexAction(Application $app)
    {
        return $app['twig']->render('pages/index.twig');
    }

    public function helloAction(Application $app, $name = '')
    {
        return $this->twig->render('page.twig', array(
            'name' => $name,
        ));
    }
}
