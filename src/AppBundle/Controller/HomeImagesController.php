<?php

namespace AppBundle\Controller;

use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Method;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use AppBundle\Entity\HomeImages;
use AppBundle\Form\HomeImagesType;
use BackendBundle\Controller\DefaultController as BackendBundleController;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;

/**
 * HomeImages controller.
 *
 * @Route("/admin/homeimagess")
 */
class HomeImagesController extends BackendBundleController
{
    
    protected $activeSideBar = 'home_images';

    /**
     * Get active side bar
     * @return type
     */
    protected function getActiveSidebar()
    {
        return $this->activeSideBar;
    }
    
    /**
     * 
     * @param type $url
     * @param type $data
     * @return type
     */
	protected function getBreadCrumbs($url = null, $data = array())
	{
		$breadcrumbs = parent::getBreadCrumbs($url);
		$breadcrumbs[] = array(
			'name' => 'homeimages.title_nav'
		);
		if($url){
			$breadcrumbs[1]['url'] = $this->generateUrl('admin_homeimages_list');
		}
		if($data && is_array($data)){
			$breadcrumbs[] = $data;
		}

		return $breadcrumbs;
	}

    
    /**
     * Lists all HomeImages entities.
     *
     * @return array
     *
     * @Route("/",  name="admin_homeimages_list")
     * @Method("GET")
     */
    public function indexAction()
    {
        $em = $this->getDoctrine()->getManager();
        $homeimagesCollection = $em->getRepository('AppBundle:HomeImages')->findBy(array());
        return $this->render(
			'AppBundle:HomeImages:list.html.twig',
			array(
				'homeimagesCollection' => $homeimagesCollection,
				'breadcrumbs' => $this->getBreadCrumbs(false),
				'active_side_bar' => $this->getActiveSidebar()
			)
		);
    }

    /**
     * Creates a new HomeImages entity.
     *
     * @Route("/new", name="admin_homeimages_create")
     * @Method({"GET", "POST"})
     */
    public function newAction(Request $request)
    {
        $homeimages = new HomeImages();
        $form = $this->createForm('AppBundle\Form\HomeImagesType', $homeimages,  array('uploadDir'=> 'uploads/images/homeimages', 'required' => true));
        $form->add('submit', SubmitType::class, array(
            'label' => $this->get('translator')->trans('app.create_btn'),
            'attr'=>array('class'=>'btn btn-success')
            ));
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $em = $this->getDoctrine()->getManager();
            $em->persist($homeimages);
            $em->flush();
            
            $this->get('session')->getFlashBag()->add('success', $this->get('translator')->trans('homeimages.create_succesfull'));

            return $this->redirectToRoute('admin_homeimages_list');
        }

        return $this->render(
			'AppBundle:HomeImages:new.html.twig',
                array(
                    'entity' => $homeimages,
                    'form' => $form->createView(),
                    'breadcrumbs' => $this->getBreadCrumbs(true, array("name" => "backend.create")),
                    'active_side_bar' => $this->getActiveSidebar()
                )
            );
    }

    /**
     * Displays a form to edit an existing HomeImages entity.
     *
     * @Route("/{id}/edit",  name="admin_homeimages_edit")
     * @Method({"GET", "POST"})
     */
    public function editAction(Request $request, HomeImages $homeimages)
    {
        
        $deleteForm = $this->createDeleteForm($homeimages);
        $editForm = $this->createForm('AppBundle\Form\HomeImagesType', $homeimages,  array('uploadDir'=> 'uploads/images/homeimages'));
        $editForm->add('submit', SubmitType::class, array(
            'label' => $this->get('translator')->trans('homeimages.save'),
            'attr'=>array('class'=>'btn btn-success')
            ));
        
        $editForm->handleRequest($request);

        if ($editForm->isSubmitted() && $editForm->isValid()) {
            $em = $this->getDoctrine()->getManager();

            $em->persist($homeimages);
            $em->flush();

            $this->get('session')->getFlashBag()->add('success', $this->get('translator')->trans('homeimages.edit_succesfull') );
            
            return $this->redirectToRoute('admin_homeimages_list');
        }

        return $this->render(
			'AppBundle:HomeImages:new.html.twig',
                array(
                    'entity' => $homeimages,
                    'form' => $editForm->createView(),
                    'delete_form' => $deleteForm->createView(),
                    'breadcrumbs' => $this->getBreadCrumbs(false),
                    'active_side_bar' => $this->getActiveSidebar()
                )
            );
    }

    /**
     * Deletes a HomeImages entity.
     *
     * @Route("/{id}",  name="admin_homeimages_delete")
     * @Method("DELETE")
     */
    public function deleteAction(Request $request, HomeImages $homeimages)
    {
        $form = $this->createDeleteForm($homeimages);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $em = $this->getDoctrine()->getManager();
            $em->remove($homeimages);
            $em->flush();
            
            $this->get('session')->getFlashBag()->add('info', $this->get('translator')->trans('homeimages.delete_succesfull'));
        }

        return $this->redirectToRoute('admin_homeimages_list');
    }

    /**
     * Creates a form to delete a HomeImages entity.
     *
     * @param HomeImages $homeimages The HomeImages entity
     *
     * @return \Symfony\Component\Form\Form The form
     */
    private function createDeleteForm(HomeImages $homeimages)
    {
        return $this->createFormBuilder()
            ->setAction($this->generateUrl('admin_homeimages_delete', array('id' => $homeimages->getId())))
            ->setMethod('DELETE')
            ->getForm()
        ;
    }
    
}
