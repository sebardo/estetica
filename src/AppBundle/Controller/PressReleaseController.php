<?php


namespace AppBundle\Controller;

use AppBundle\Entity\Client;
use AppBundle\Entity\PressRelease;
use AppBundle\Event\PressReleaseEvent;
use AppBundle\PressReleaseEvents;
use AppBundle\Services\RandomString;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Method;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Security;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use BackendBundle\Controller\DefaultController as BackendBundleController;

/**
 * Class PressReleaseController
 * @Route("/admin/press-release")
 */
class PressReleaseController extends BackendBundleController
{
	protected $activeSideBar = 'press_release';

	protected function getBreadCrumbs($url = null, $data = array())
	{
		$breadcrumbs = parent::getBreadCrumbs($url);

		$breadcrumbs[] = array(
			'name' => 'press_release.title_nav'
		);

		if($url){
			$breadcrumbs[1]['url'] = $this->generateUrl('admin_press_release_list');
		}

		if($data && is_array($data)){
			$breadcrumbs[] = $data;
		}

		return $breadcrumbs;
	}

	/**
	 * List press release entities
	 *
	 * @Route("/list", name="admin_press_release_list")
	 * @Method({"GET","POST"})
	 * @Security("has_role('ROLE_CLIENT')")
	 * @return Response
	 */
	public function indexPressReleaseAction()
	{
//		$deleteFormCollection = array();
		/** @var Client $client */
		$client = $this->container->get('security.token_storage')->getToken()->getUser();

		$pressReleaseCollection = $this->getPressReleaseCollectionByRoleOrId($client);

		return $this->render(
			'AppBundle:PressRelease:list.html.twig',
			array(
				'pressReleaseCollection' => $pressReleaseCollection,
//				'deleteFormCollection' => $deleteFormCollection,
				'breadcrumbs' => $this->getBreadCrumbs(false),
				'active_side_bar' => $this->getActiveSidebar()
			)
		);
	}

	private function getPressReleaseCollectionByRoleOrId(Client $client)
	{
		$pressReleaseManager = $this->container->get('webapp.manager.press_release_manager');
		if($this->isGranted('ROLE_ADMIN')) {
			$pressReleaseCollection = $pressReleaseManager->getBy(array());
		}else {
			$pressReleaseCollection = $pressReleaseManager->getBy(array('client' => $client));
		}

		return $pressReleaseCollection;
	}

	/**
	 * Create press release entity.
	 *
	 * @param Request $request
	 *
	 * @Route("/create", name="admin_press_release_create")
	 * @Method({"GET", "POST"})
	 * @Security("has_role('ROLE_CLIENT')")
	 * @return Response
	 */
	public function createPressReleaseAction(Request $request)
	{
		$entity = new PressRelease();
		/** @var Client $client */
		$client = $this->container->get('security.token_storage')->getToken()->getUser();
		$form = $this->createForm('AppBundle\Form\PressReleaseType', $entity);
		$form->add('submit', 'Symfony\Component\Form\Extension\Core\Type\SubmitType', array('label' => $this->get('translator')->trans('app.create_btn'),'attr'=>array('class'=>'btn btn-success')));
		$form->handleRequest($request);

		if ($form->isSubmitted() && $form->isValid()) {
			$em = $this->getDoctrine()->getManager();
			$entity->setClient($client);
			$em->persist($entity);
			$em->flush();

			//Send Email
			$this->get('event_dispatcher')->dispatch(
				PressReleaseEvents::PRESS_RELEASE_CREATED,
				new PressReleaseEvent($entity)
			);

			$this->get('session')->getFlashBag()->add('success', $this->get('translator')->trans('press_release.create_succesfull'));

			return $this->redirectToRoute('admin_press_release_list');
		}

		return $this->render('AppBundle:PressRelease:new.html.twig', array(
			'entity' => $entity,
			'form' => $form->createView(),
			'breadcrumbs' => $this->getBreadCrumbs(true, array("name" => "backend.create")),
			'active_side_bar' => $this->getActiveSidebar()
		));
	}

	/**
	 * Displays a show view to an existing press release entity.
	 *
	 * @param Request $request
	 * @param PressRelease $entity
	 *
	 * @Route("/{id}/edit", name="admin_press_release_show")
	 * @Method({"GET", "POST"})
	 * @Security("has_role('ROLE_CLIENT')")
	 *
	 * @return \Symfony\Component\HttpFoundation\RedirectResponse|Response
	 */
	public function showCreativityProposalAction(Request $request, PressRelease $entity)
	{
		/** @var Client $client */
		$client = $this->container->get('security.token_storage')->getToken()->getUser();

		if($entity->getClient() !== $client) {
			$this->get('session')->getFlashBag()->add('success', $this->get('translator')->trans('validator.cannot_see_this'));

			return $this->redirectToRoute('admin_press_release_list');
		}

		return $this->render('AppBundle:PressRelease:show.html.twig', array(
			'entity' => $entity,
			'breadcrumbs' => $this->getBreadCrumbs(true, array("name" => "backend.show")),
			'active_side_bar' => $this->getActiveSidebar()
		));
	}

//	/**
//	 * Displays a form to edit an existing press release entity.
//	 *
//	 * @param Request $request
//	 * @param PressRelease $entity
//	 *
//	 * @Route("/{id}/edit", name="admin_press_release_edit")
//	 * @Method({"GET", "POST"})
//	 * @Security("has_role('ROLE_CLIENT')")
//	 *
//	 * @return \Symfony\Component\HttpFoundation\RedirectResponse|Response
//	 */
//	public function editPressReleaseAction(Request $request, PressRelease $entity)
//	{
//		/** @var Client $client */
//		$client = $this->container->get('security.token_storage')->getToken()->getUser();
//		if($entity->getClient() !== $client) {
//			return $this->redirectToRoute('admin_press_release_list');
//		}
//
//		$editForm = $this->createForm('AppBundle\Form\PressReleaseType', $entity, array('edit_form' => true));
//		$editForm->add('submit', 'Symfony\Component\Form\Extension\Core\Type\SubmitType', array('label' => $this->get('translator')->trans('app.edit_btn'),'attr'=>array('class'=>'btn btn-success')));
//		$editForm->handleRequest($request);
//
//		if ($editForm->isSubmitted() && $editForm->isValid()) {
//			$em = $this->getDoctrine()->getManager();
//			$em->persist($entity);
//			$em->flush();
//
//			$this->get('session')->getFlashBag()->add('success', $this->get('translator')->trans('press_release.edit_succesfull'));
//
//			return $this->redirectToRoute('admin_press_release_edit', array('id' => $entity->getId()));
//		}
//
//		return $this->render('AppBundle:PressRelease:edit.html.twig', array(
//			'entity' => $entity,
//			'form' => $editForm->createView(),
//			'breadcrumbs' => $this->getBreadCrumbs(true, array("name" => "backend.edit")),
//			'active_side_bar' => $this->getActiveSidebar()
//		));
//	}
//
//	/**
//	 * Deletes a press release entity.
//	 *
//	 * @param Request $request
//	 * @param PressRelease  $entity
//	 *
//	 * @Route("/{id}", name="admin_press_release_delete")
//	 * @Method("DELETE")
//	 * @Security("has_role('ROLE_CLIENT')")
//	 *
//	 * @return \Symfony\Component\HttpFoundation\RedirectResponse
//	 */
//	public function deletePressReleaseAction(Request $request, PressRelease $entity)
//	{
//		$form = $this->createDeleteForm($entity);
//		$form->handleRequest($request);
//
//		if ($form->isSubmitted() && $form->isValid()) {
//			$em = $this->getDoctrine()->getManager();
//			$em->remove($entity);
//			$em->flush();
//			$this->get('session')->getFlashBag()->add('success', $this->get('translator')->trans('press_release.delete_succesfull'));
//		}
//
//		return $this->redirectToRoute('admin_press_release_list');
//	}
//
//	/**
//	 * Creates a form to delete a press release entity.
//	 *
//	 * @param PressRelease $entity
//	 *
//	 * @return \Symfony\Component\Form\Form The form
//	 */
//	private function createDeleteForm(PressRelease $entity)
//	{
//		return $this->createFormBuilder()
//			->add('submit', 'Symfony\Component\Form\Extension\Core\Type\SubmitType', array('label' =>'app.delete', 'attr' => array('class' => 'btn btn-danger')))
//			->setAction($this->generateUrl('admin_press_release_delete', array('id' => $entity->getId())))
//			->setMethod('DELETE')
//			->getForm()
//			;
//	}
}