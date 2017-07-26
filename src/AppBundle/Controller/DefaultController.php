<?php

namespace AppBundle\Controller;

use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use BackendBundle\Controller\DefaultController as BackendBundleController;
use Symfony\Component\HttpFoundation\Request;

class DefaultController extends BackendBundleController
{
    /**
     * @Route("/", name="homepage")
     */
    public function indexAction(Request $request)
    {
        // replace this example code with whatever you need
        return $this->render('@App/index.html.twig', array(
            'breadcrumbs' => $this->getBreadCrumbs(true, array("name" => "backend.homepage")),
            'active_side_bar' => $this->getActiveSidebar()
        ));
    }
}
