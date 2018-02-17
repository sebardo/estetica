<?php

namespace EditorBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Method;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Template;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Security;
use EditorBundle\Entity\Template as Templating;
use EditorBundle\Form\TemplateType;
use EditorBundle\Form\TemplateDeliveryType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use AppBundle\Event\OrderEvent;
use AppBundle\OrderEvents;
use EditorBundle\Form\TemplatePrintType;

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
        $supportCollectionPost = TemplateType::getSelectSupportsPost();
        $categoryCollection = TemplateType::getSelectCategories();
        $subcategoryCollection = TemplateType::getSelectSubcategories('all');
                
        return array(
            'supportCollection' => $supportCollection,
            'supportCollectionPost' => $supportCollectionPost,
            'categoryCollection' => $categoryCollection,
            'subcategoryCollection' => $subcategoryCollection
        ); 
    }
    
    /**
     * @Route("/admin/history")
     * @Template()
     */
    public function historyAction()
    {
        
        $supportCollection = TemplateType::getSelectSupports();
        $categoryCollection = TemplateType::getSelectCategories();
        $subcategoryCollection = TemplateType::getSelectSubcategories('all');
                
        return array(
            'supportCollection' => $supportCollection,
            'categoryCollection' => $categoryCollection,
            'subcategoryCollection' => $subcategoryCollection
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
            $jsonList->setSubCategory($request->query->get('subcategory'));
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
            
            $this->get('session')->getFlashBag()->add('success', $this->get('translator')->trans('template.created'));
            
            return $this->redirectToRoute('editor_editor_edit', array('id' => $template->getId()));
        }

        return array(
            'entity' => $template,
            'form' => $form->createView(),
        );
    }
    
    /**
     * Creates a new contract entity.
     *
     * @Route("/admin/editor/new-user")
     * @Method({"GET", "POST"})
     * @Template("EditorBundle:Editor:new.user.html.twig")
     */
    public function newUserAction(Request $request)
    {
        $em = $this->getDoctrine()->getManager();
        
        $support = $request->query->get('support');
        $category = $request->query->get('category');
        $subcategory = $request->query->get('subcategory');
        
        $selectedEntities = $em->getRepository('EditorBundle:Template')->findBy(array(
            'support' => $support,
            'category' => $category,
            'subcategory' => $subcategory,
            'parentTemplate' => null
        ));
        
        if($request->query->has('id') && $request->query->get('id') != ''){
            $id = $request->query->get('id');
            $template = $em->getRepository('EditorBundle:Template')->find($id);
        }else{
            $template = $em->getRepository('EditorBundle:Template')->find($selectedEntities[0]);
        }
        
        
       // $template = new Templating();
        $form = $this->createForm('EditorBundle\Form\TemplateType', $template);
        $form->handleRequest($request);
       
        if ($form->isSubmitted() && $form->isValid()) {
            
            
            $em->persist($template);
            $em->flush();
            
            $this->get('session')->getFlashBag()->add('success', $this->get('translator')->trans('template.created'));
            
            return $this->redirectToRoute('editor_editor_edit', array('id' => $template->getId()));
        }

        return array(
            'entity' => $template,
            'form' => $form->createView(),
            'selectedEntities' => $selectedEntities
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
        }else{
            $em = $this->getDoctrine()->getManager();
            $temp = $em->getRepository('EditorBundle:Template')->findOneBy(array(
                'parentTemplate' => $template->getId(),
                'client' => $this->getUser()->getId()
            ));
        
            if($temp instanceof Templating){
                
                $path = parse_url($request->query->get('referer'), PHP_URL_PATH);
                $query = parse_url($request->query->get('referer'), PHP_URL_QUERY);
                $qArr = explode('&', $query);
                
                $returnValues = array();
                foreach ($qArr as $value) {
                    $arr = explode('=', $value);
                    $returnValues[$arr[0]] = $arr[1];
                }
                unset($returnValues['id']);
                
                $returnValues2 = array();
                foreach ($returnValues as $key => $value) {
                    $returnValues2[] = $key.'='.$value;
                }
                
                $newUrlReferer = $path.'?'. implode('&', $returnValues2);
                return $this->redirect($newUrlReferer.'&id='.$temp->getId());
            }
            $templateNew = $this->cloneTemplate($template);
            
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
            
            $client = $this->container->get('security.token_storage')->getToken()->getUser();
            if($template->getClient() == $client) {
                $this->get('session')->getFlashBag()->add('success', $this->get('translator')->trans('template.created'));
                if($template->getSupport() === Templating::SUPPORT_FLYERS) {
                    return $this->redirectToRoute('editor_editor_complete', array('id' => $template->getId()));
                }else{
                    return $this->redirectToRoute('editor_editor_print', array('id' => $template->getId()));
                }
            }
            $this->get('session')->getFlashBag()->add('success', $this->get('translator')->trans('template.edited'));
            
            return $this->redirectToRoute('editor_editor_edit', array('id' => $template->getId()));
        }

        return array(
            'entity' => $template,
            'edit_form' => $form->createView(),
        );
    }
    
    /**
     * Deletes a event entity.
     *
     * @Route("/admin/editor/delete/{id}")
     * @Method("GET")
     */
    public function deleteAction(Request $request,Templating $template)
    {
        if(count($template->getChilds())==0){
            $em = $this->getDoctrine()->getManager();
            $em->remove($template);
            $em->flush();

            $this->get('session')->getFlashBag()->add('success', $this->get('translator')->trans('template.deleted'));    
        }else{
            $this->get('session')->getFlashBag()->add('success', $this->get('translator')->trans('template.no.deleted'));    
        }
        

        return $this->redirectToRoute('editor_editor_index');
    }
    
    /**
    * Printed custom creativity on front.
    *
    * @Route("/admin/editor/complete/{id}" )
    * @Method({"GET", "POST"})
    * @Security("has_role('ROLE_CLIENT')")
    * @return Response
    */
    public function completeAction(Request $request, Templating $template)
    {
       
        if($template->getSupport() === Templating::SUPPORT_FLYERS) {
            $form = $this->createForm(new TemplateDeliveryType(), $template);
            $form->add('submit', SubmitType::class, array(
                        'label' => $this->get('translator')->trans('app.create_btn'),
                        'attr'=>array('class'=>'btn btn-success'))
                    );
            $form->handleRequest($request);

            if ($form->isSubmitted() && $form->isValid()) {
                $em = $this->getDoctrine()->getManager();
                $template->setStatus(Templating::COMPLETED_STATE);
                $em->persist($template);
                $em->flush();

                $this->get('session')->getFlashBag()->add('success', $this->get('translator')->trans('creativity_order.send_notification'));

                return $this->redirectToRoute('editor_editor_index');
            }
        } else {
            $this->sendNotification($template);
            $this->get('session')->getFlashBag()->add('success', $this->get('translator')->trans('creativity_order.send_notification'));

            return $this->redirectToRoute('editor_editor_index');
        }

        return $this->render(
                'EditorBundle:Editor:complete.html.twig',
                array(
                    'entity' => $template,
                    'form' => $form->createView()
                )
        );
    }
    
    /**
     * Upload file
     *
     * @Route("/admin/editor/upload/{id}")
     * @Method({"GET", "POST"})
     */
    public function uploadAction(Request $request, Templating $template)
    {
        $em = $this->getDoctrine()->getManager();
        $path = null;
        if($request->request->has('data')){
            $path = "/uploads/templates/". uniqid().".pdf";
            $data = base64_decode($request->request->get('data'));
            //store file
            file_put_contents( __DIR__."/../../../web".$path, $data);
            //update entity
            $template->setPdfPath($path);
            $em->flush();
        }
        
        if($request->request->has('img1')){
            $template->setPreviewImage($request->request->get('img1'));
            $em->flush();
        }
        if($request->request->has('img2')){
            $template->setPreviewImage2($request->request->get('img2'));
            $em->flush();
        }
        
        return new JsonResponse($path);
    }
    
    private function sendNotification(Templating $template)
    {
        //Send Email
        $this->get('event_dispatcher')->dispatch(
                OrderEvents::ORDER_CREATED,
                new OrderEvent($template)
        );
    }
    
    /**
    * Printed custom creativity on front.
    *
    * @Route("/admin/editor/print/{id}" )
    * @Method({"GET", "POST"})
    * @Security("has_role('ROLE_CLIENT')")
    * @return Response
    */
   public function printAction(Request $request, Templating $template)
   {
        $form = $this->createForm(new TemplatePrintType(), $template);
        $form->handleRequest($request);
        
        if ($form->isSubmitted() && $form->isValid()) {
            $em = $this->getDoctrine()->getManager();
            $em->flush();

            if($template->getPrint()){
                $this->sendNotification($template);
                $this->get('session')->getFlashBag()->add('success', $this->get('translator')->trans('creativity_order.send_notification'));
            }else{
                $this->get('session')->getFlashBag()->add('success', $this->get('translator')->trans('creativity_order.send_notification_fail'));
            }

            return $this->redirectToRoute('editor_editor_index');
        }

        return $this->render(
            'EditorBundle:Editor:print.html.twig',
            array(
                'entity' => $template,
                'form' => $form->createView()
            )
        );
   }
}
