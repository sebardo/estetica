<?php

namespace AppBundle\Controller;

use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Method;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use AppBundle\Entity\Slider;
use AppBundle\Form\SliderType;
use BackendBundle\Controller\DefaultController as BackendBundleController;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;

/**
 * Slider controller.
 *
 * @Route("/admin/sliders")
 */
class SliderController extends BackendBundleController
{
    
    protected $activeSideBar = 'sliders';

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
			'name' => 'slider.title_nav'
		);
		if($url){
			$breadcrumbs[1]['url'] = $this->generateUrl('admin_slider_list');
		}
		if($data && is_array($data)){
			$breadcrumbs[] = $data;
		}

		return $breadcrumbs;
	}

    
    /**
     * Lists all Slider entities.
     *
     * @return array
     *
     * @Route("/",  name="admin_slider_list")
     * @Method("GET")
     */
    public function indexAction()
    {
        $em = $this->getDoctrine()->getManager();
        $sliderCollection = $em->getRepository('AppBundle:Slider')->findBy(array());
        return $this->render(
			'AppBundle:Slider:list.html.twig',
			array(
				'sliderCollection' => $sliderCollection,
				'breadcrumbs' => $this->getBreadCrumbs(false),
				'active_side_bar' => $this->getActiveSidebar()
			)
		);
    }

    /**
     * Creates a new Slider entity.
     *
     * @Route("/new", name="admin_slider_create")
     * @Method({"GET", "POST"})
     */
    public function newAction(Request $request)
    {
        $slider = new Slider();
        $form = $this->createForm('AppBundle\Form\SliderType', $slider,  array('uploadDir'=> 'uploads/images/slider' ));
        $form->add('submit', SubmitType::class, array(
            'label' => $this->get('translator')->trans('app.create_btn'),
            'attr'=>array('class'=>'btn btn-success')
            ));
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $em = $this->getDoctrine()->getManager();
            $em->persist($slider);
            $em->flush();
            
            $this->get('session')->getFlashBag()->add('success', $this->get('translator')->trans('slider.create_succesfull'));

            return $this->redirectToRoute('admin_slider_list');
        }

        return $this->render(
			'AppBundle:Slider:new.html.twig',
                array(
                    'entity' => $slider,
                    'form' => $form->createView(),
                    'breadcrumbs' => $this->getBreadCrumbs(true, array("name" => "backend.create")),
                    'active_side_bar' => $this->getActiveSidebar()
                )
            );
    }

    /**
     * Finds and displays a Slider entity.
     *
     * @Route("/{id}", name="admin_slider_show")
     * @Method("GET")
     */
    public function showAction(Slider $slider)
    {
        $deleteForm = $this->createDeleteForm($slider);

        return $this->render(
			'AppBundle:Slider:show.html.twig', array(
            'entity' => $slider,
            'delete_form' => $deleteForm->createView(),
			'breadcrumbs' => $this->getBreadCrumbs(true, array("name" => "backend.show")),
			'active_side_bar' => $this->getActiveSidebar()
        ));
    }

    /**
     * Displays a form to edit an existing Slider entity.
     *
     * @Route("/{id}/edit", name="admin_slider_edit")
     * @Method({"GET", "POST"})
     */
    public function editAction(Request $request, Slider $slider)
    {
        $deleteForm = $this->createDeleteForm($slider);
        $editForm = $this->createForm('AppBundle\Form\SliderType', $slider,  array('uploadDir'=> 'uploads/images/slider' ));
        $editForm->add('submit', SubmitType::class, array(
            'label' => $this->get('translator')->trans('slider.save'),
            'attr'=>array('class'=>'btn btn-success')
            ));
         
        $editForm->handleRequest($request);

        if ($editForm->isSubmitted() && $editForm->isValid()) {
            $em = $this->getDoctrine()->getManager();
            
            if($slider->getRemoveImage()){
                $slider->setImage(null);
            }
            
            $em->persist($slider);
            $em->flush();

            $this->get('session')->getFlashBag()->add('success', $this->get('translator')->trans('slider.edit_succesfull'));
            
            return $this->redirectToRoute('admin_slider_list');
        }

  
        return $this->render(
			'AppBundle:Slider:edit.html.twig',
                array(
                    'entity' => $slider,
                    'form' => $editForm->createView(),
                    'breadcrumbs' => $this->getBreadCrumbs(true, array("name" => "backend.create")),
                    'active_side_bar' => $this->getActiveSidebar()
                )
            );
    }

    /**
     * Deletes a Slider entity.
     *
     * @Route("/{id}", name="admin_slider_delete")
     * @Method("DELETE")
     */
    public function deleteAction(Request $request, Slider $slider)
    {
        $form = $this->createDeleteForm($slider);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $em = $this->getDoctrine()->getManager();
            $em->remove($slider);
            $em->flush();
            
            $this->get('session')->getFlashBag()->add('info', $this->get('translator')->trans('slider.delete_succesfull'));
        }

        return $this->redirectToRoute('admin_slider_list');
    }

    /**
     * Creates a form to delete a Slider entity.
     *
     * @param Slider $slider The Slider entity
     *
     * @return \Symfony\Component\Form\Form The form
     */
    private function createDeleteForm(Slider $slider)
    {
        return $this->createFormBuilder()
            ->setAction($this->generateUrl('admin_slider_delete', array('id' => $slider->getId())))
            ->setMethod('DELETE')
            ->getForm()
        ;
    }
    
    /**
     * Delete by ajax  a slider entity.
     *
     * @Route("/{id}/delete", name="admin_slider_deleteajax")
     * @Method("GET")
     */
    public function deleteAjaxAction(Request $request, Slider $slider)
    {
        $em = $this->getDoctrine()->getManager();
        
        $em->remove($slider);
        $em->flush();

        $this->get('session')->getFlashBag()->add('info', $this->get('translator')->trans('slider.delete_succesfull'));
        
        return $this->redirectToRoute('admin_slider_list');
      
    }
}
