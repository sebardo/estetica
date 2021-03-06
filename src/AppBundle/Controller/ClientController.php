<?php


namespace AppBundle\Controller;

use AppBundle\Entity\Client;
use AppBundle\Services\RandomString;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Method;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Security;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use BackendBundle\Controller\DefaultController as BackendBundleController;

/**
 * Class ClientController
 * @Route("/admin/client")
 */
class ClientController extends BackendBundleController
{
	protected $activeSideBar = 'client';

	protected function getBreadCrumbs($url = null, $data = array())
	{
		$breadcrumbs = parent::getBreadCrumbs($url);

		$breadcrumbs[] = array(
			'name' => 'client.title_nav'
		);

		if($url){
			$breadcrumbs[1]['url'] = $this->generateUrl('admin_client_list');
		}

		if($data && is_array($data)){
			$breadcrumbs[] = $data;
		}

		return $breadcrumbs;
	}

	/**
	 * List client entities
	 *
	 * @Route("/", name="admin_client_list")
	 * @Method({"GET","POST"})
	 * @Security("has_role('ROLE_ADMIN')")
	 * @return Response
	 */
	public function indexClientAction()
	{
		$deleteFormCollection = array();

		$clientManager = $this->container->get('webapp.manager.client_manager');

		$clientCollection = $clientManager->getBy(array("isAdministrator" => false));
		foreach ($clientCollection as $client) {
			if($client instanceof Client){
				$deleteFormCollection[$client->getId()] = $this->createDeleteForm($client)->createView();
			}
		}

		return $this->render(
			'AppBundle:Client:list.html.twig',
			array(
				'clientCollection' => $clientCollection,
				'deleteFormCollection' => $deleteFormCollection,
				'breadcrumbs' => $this->getBreadCrumbs(false),
				'active_side_bar' => $this->getActiveSidebar()
			)
		);
	}

         /**
        * Lists all entities.
        *
        * @Route("/list.{_format}", name="admin_client_listjson", requirements={ "_format" = "json" }, defaults={ "_format" = "json" })
        * @Method("GET")  
        * @return Response   
        */
       public function listJsonAction(Request $request)
       {
           $em = $this->getDoctrine()->getManager();


           /** @var \AdminBundle\Services\DataTables\JsonList $jsonList */
           $jsonList = $this->get('json_list');
           $jsonList->setRepository($em->getRepository('AppBundle:Client'));

           $response = $jsonList->get();


           return new JsonResponse($response);
       }

	/**
	 * Create client entity.
	 *
	 * @param Request $request
	 *
	 * @Route("/create", name="admin_client_create")
	 * @Method({"GET", "POST"})
	 * @Security("has_role('ROLE_ADMIN')")
	 * @return Response
	 */
	public function createClientAction(Request $request)
	{
		$entity = new Client();
		$form = $this->createForm('AppBundle\Form\ClientType', $entity, array('edit_form' => false));
		$form->add('submit', 'Symfony\Component\Form\Extension\Core\Type\SubmitType', array('label' => $this->get('translator')->trans('app.create_btn'),'attr'=>array('class'=>'btn btn-success')));
		$form->handleRequest($request);

		if ($form->isSubmitted() && $form->isValid()) {
			$plainPassword = $this->get('webapp.manager.client_manager')->createCredentials($entity);
			$this->get('webapp.manager.client_manager')->encodePassword($entity);
			$this->get('webapp.manager.client_manager')->createClient($entity);

			//Mailer Service
			$this->get('webapp.services.mailer')->sendMail($entity, $plainPassword);

			$this->get('session')->getFlashBag()->add('success', $this->get('translator')->trans('client.create_succesfull'));

			return $this->redirectToRoute('admin_client_list', array('id' => $entity->getId()));
		}

		return $this->render('AppBundle:Client:new.html.twig', array(
			'entity' => $entity,
			'form' => $form->createView(),
			'breadcrumbs' => $this->getBreadCrumbs(true, array("name" => "backend.create")),
			'active_side_bar' => $this->getActiveSidebar()
		));
	}

	/**
	 * Displays a form to edit an existing client entity.
	 *
	 * @param Request $request
	 * @param Client $entity
	 *
	 * @Route("/{id}/edit", name="admin_client_edit")
	 * @Method({"GET", "POST"})
	 * @Security("has_role('ROLE_CLIENT')")
	 *
	 * @return \Symfony\Component\HttpFoundation\RedirectResponse|Response
	 */
	public function editClientAction(Request $request, Client $entity)
	{
		/** @var Client $client */
		$client = $this->container->get('security.token_storage')->getToken()->getUser();
		if($entity !== $client && !$this->isGranted('ROLE_ADMIN')) {
			return $this->redirectToRoute('homepage');
		}

		$oldPassword = $entity->getPassword();
		$editForm = $this->createForm('AppBundle\Form\ClientType', $entity, array('edit_form' => true, 'admin_user' => $this->isGranted('ROLE_ADMIN')));
		$editForm->add('submit', 'Symfony\Component\Form\Extension\Core\Type\SubmitType', array('label' => $this->get('translator')->trans('app.save_btn'),'attr'=>array('class'=>'btn btn-success')));
		$editForm->handleRequest($request);

		if ($editForm->isSubmitted() && $editForm->isValid()) {
			$passwordFieldRequest = $request->request->get($editForm->getName())['password'];
			if(!empty($passwordFieldRequest)){
				$this->get('webapp.manager.client_manager')->encodePassword($entity);
			}else{
				$entity->setPassword($oldPassword);
			}
			$this->get('webapp.manager.client_manager')->updateClient($entity);

			$this->get('session')->getFlashBag()->add('success', $this->get('translator')->trans('client.edit_succesfull'));
		}

		return $this->render('AppBundle:Client:edit.html.twig', array(
			'entity' => $entity,
			'form' => $editForm->createView(),
			'breadcrumbs' => $this->getBreadCrumbs(true, array("name" => "backend.edit")),
			'active_side_bar' => $this->getActiveSidebar()
		));
	}

	/**
	 * Deletes a client entity.
	 *
	 * @param Request $request
	 * @param Client  $entity
	 *
	 * @Route("/{id}", name="admin_client_delete")
	 * @Method("DELETE")
	 * @Security("has_role('ROLE_ADMIN')")
	 *
	 * @return \Symfony\Component\HttpFoundation\RedirectResponse
	 */
	public function deleteClientAction(Request $request, Client $entity)
	{
		$form = $this->createDeleteForm($entity);
		$form->handleRequest($request);

		if ($form->isSubmitted() && $form->isValid()) {
			$this->get('webapp.manager.client_manager')->save($entity);
			$this->get('session')->getFlashBag()->add('success', $this->get('translator')->trans('client.delete_succesfull'));
		}

		return $this->redirectToRoute('admin_client_list');
	}
        
        /**
	 * Deletes a client entity.
	 *
	 * @param Request $request
	 * @param Client  $entity
	 *
	 * @Route("/{id}/delete", name="admin_client_delete2")
	 * @Method("GET")
	 * @Security("has_role('ROLE_ADMIN')")
	 *
	 * @return \Symfony\Component\HttpFoundation\RedirectResponse
	 */
	public function deleteIdClientAction(Request $request, Client $entity)
	{

                $this->get('webapp.manager.client_manager')->remove($entity);
                $this->get('session')->getFlashBag()->add('success', $this->get('translator')->trans('client.delete_succesfull'));


		return $this->redirectToRoute('admin_client_list');
	}

	/**
	 * Creates a form to delete a client entity.
	 *
	 * @param Client $entity
	 *
	 * @return \Symfony\Component\Form\Form The form
	 */
	private function createDeleteForm(Client $entity)
	{
		return $this->createFormBuilder()
			->add('submit', 'Symfony\Component\Form\Extension\Core\Type\SubmitType', array('label' =>'app.delete', 'attr' => array('class' => 'btn btn-danger')))
			->setAction($this->generateUrl('admin_client_delete', array('id' => $entity->getId())))
			->setMethod('DELETE')
			->getForm()
			;
	}
        
    /**
     * Enable/Disable  a page entity.
     *
     * @Route("/{id}/enable")
     * @Method("POST")
     */
    public function enableAction(Request $request, Client $entity)
    {
        $em = $this->getDoctrine()->getManager();
        
        if($entity->isActive()){
            $entity->setActive(false);
        }else{
            $entity->setActive(true);
        }
        
        $em->flush($entity);

        $sub = new \stdClass();
        $sub->status = 'success';
        $sub->id = $entity->getId();
        $sub->active = $entity->isActive();
        return new JsonResponse($sub);

    }
}