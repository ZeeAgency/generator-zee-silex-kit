<?php
namespace App\Modules\Render\Controller;

use App\Modules\Render\Module;
use Silex\Application;
use Zee;

class RenderController extends Zee\Controller
{
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
