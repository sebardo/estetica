<?php

namespace EditorBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Method;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Template;
use EditorBundle\Entity\Template as Templating;
use EditorBundle\Form\TemplateType;

class EditorController extends Controller
{
    
     /**
     * Returns the DataTables i18n file
     *
     * @return Response
     *
     * @Route("/admin/dataTables.{_format}" , requirements={ "_format" = "json" }, defaults={ "_format" = "json" })
     * @Method("GET")
     */
    public function getDataTablesI18nAction()
    {
        $locale = $this->get('request_stack')->getCurrentRequest()->getLocale();
        $format = $this->get('request_stack')->getCurrentRequest()->getRequestFormat();

        return $this->render(
            'EditorBundle:DataTables_i18n:'.$locale.'.txt.'.$format
        );
    }
    
    
    /**
     * @Route("/admin/editor")
     * @Template()
     */
    public function indexAction()
    {
        
        $supportCollection = TemplateType::getSelectSupports();
        $categoryCollection = TemplateType::getSelectCategories();
                
        return array(
            'supportCollection' => $supportCollection,
            'categoryCollection' => $categoryCollection
        ); 
    }
    
     /**
     * Lists all ticket entities.
     *
     * @Route("/admin/editor/list.{_format}", requirements={ "_format" = "json" }, defaults={ "_format" = "json" })
     * @Method("GET")     
     */
    public function listJsonAction(Request $request)
    {
        $em = $this->getDoctrine()->getManager();

        
        /** @var \AdminBundle\Services\DataTables\JsonList $jsonList */
        $jsonList = $this->get('json_list');
        $jsonList->setRepository($em->getRepository('EditorBundle:Template'));
        if($request->query->has('client')){
            $jsonList->setEntityId($request->query->get('client'));
        }else{
            $jsonList->setEntityId($request->query->get('support'));
            $jsonList->setCategory($request->query->get('category'));
        }
        
        $response = $jsonList->get();
        

        return new JsonResponse($response);
    }

    /**
     * Creates a new contract entity.
     *
     * @Route("/admin/editor/new")
     * @Method({"GET", "POST"})
     * @Template()
     */
    public function newAction(Request $request)
    {
        $template = new Templating();
        $form = $this->createForm('EditorBundle\Form\TemplateType', $template);
        $form->handleRequest($request);
       
        if ($form->isSubmitted() && $form->isValid()) {
            
            $em = $this->getDoctrine()->getManager();
            $em->persist($template);
            $em->flush();
            
            $this->get('session')->getFlashBag()->add('success', 'template.created');
            
            return $this->redirectToRoute('editor_editor_edit', array('id' => $template->getId()));
        }

        return array(
            'entity' => $template,
            'form' => $form->createView(),
        );
    }
    
    /**
     * @Route("/admin/editor/show/{id}")
     * @Template()
     */
    public function showAction(Templating $template)
    {
        return array(
            'entity' => $template
            );
    }
    
    /**
     * @Route("/admin/editor/clone/{id}")
     * @Template()
     */
    public function cloneAction(Request $request, Templating $template)
    {
        if($request->isXmlHttpRequest()){
            
            $templateNew = $this->cloneTemplate($template);

            return new JsonResponse($templateNew->getId());
        }
        
        return new JsonResponse(null);
    }
    
    public function cloneTemplate(Templating $template) 
    {
        $templateNew = clone $template;
        $templateNew->setClient($this->getUser());
        $templateNew->setParentTemplate($template);
        $template->addChild($templateNew);
        $em = $this->getDoctrine()->getManager();
        $em->persist($templateNew);
        $em->flush();
        
        return $templateNew;
    }
    /**
     * Creates a new contract entity.
     *
     * @Route("/admin/editor/edit/{id}")
     * @Method({"GET", "POST"})
     * @Template()
     */
    public function editAction(Request $request, Templating $template)
    {
        $form = $this->createForm('EditorBundle\Form\TemplateType', $template);
        $form->handleRequest($request);
       
        if ($form->isSubmitted() && $form->isValid()) {
            
            $em = $this->getDoctrine()->getManager();
            $em->flush();
            
            $this->get('session')->getFlashBag()->add('success', 'template.edited');
            
            return $this->redirectToRoute('editor_editor_show', array('id' => $template->getId()));
        }

        return array(
            'entity' => $template,
            'edit_form' => $form->createView(),
        );
    }
}
