<?php

namespace BackendBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;

class DefaultController extends Controller
{
    protected $activeSideBar = null;

    /**
     * @Route("/json/flash_bag", name="flash_bag", defaults={"_format"="json"})
     */
    public function flashBagAction()
    {
        return new JsonResponse($this->get('session')->getFlashBag()->all());
    }

    public function navbarRightAction()
    {
        return $this->render('BackendBundle:Default:partials/nav_right.html.twig');
    }

    public function mainSidebarAction($active)
    {
        if($this->get('templating')->exists('AppBundle::partials/main_sidebar.html.twig')){
            return $this->render('AppBundle::partials/main_sidebar.html.twig', array(
                'active' => $active
            ));
        }

        return $this->render('BackendBundle:Default:partials/main_sidebar.html.twig', array(
            'active' => $active
        ));
    }

    protected function getActiveSidebar()
    {
        return $this->activeSideBar;
    }

    protected function getBreadCrumbs($url = null, $data = array())
    {
        $breadcrumbs = array(
            array(
                'name' => 'home.title',
                'url' => $this->generateUrl('homepage')
            ),
//            array(
//                'name' => 'answer.title_nav'
//            )
        );

//        if($url){
//            $breadcrumbs[1]['url'] = $this->generateUrl('admin_user_list');
//        }

        return $breadcrumbs;
    }
}
