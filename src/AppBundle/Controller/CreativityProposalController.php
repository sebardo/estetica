<?php

namespace AppBundle\Controller;

use AppBundle\Entity\Client;
use AppBundle\Entity\CreativityProposal;
use AppBundle\Event\ProposalEvent;
use AppBundle\ProposalEvents;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Method;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Security;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use BackendBundle\Controller\DefaultController as BackendBundleController;

/**
 * Class CreativityProposalController
 * @Route("/admin/creativity-proposal")
 */
class CreativityProposalController extends BackendBundleController
{
	protected $activeSideBar = 'creativity-proposal';

	protected function getBreadCrumbs($url = null, $data = array())
	{
		$breadcrumbs = parent::getBreadCrumbs($url);

		$breadcrumbs[] = array(
			'name' => 'creativity_proposal.title_nav'
		);

		if($url){
			$breadcrumbs[1]['url'] = $this->generateUrl('admin_creativity_proposal_list');
		}

		if($data && is_array($data)){
			$breadcrumbs[] = $data;
		}

		return $breadcrumbs;
	}

	/**
	 * List creativity proposal entities
	 *
	 * @Route("/list", name="admin_creativity_proposal_list")
	 * @Method({"GET","POST"})
	 * @Security("has_role('ROLE_ADMIN')")
	 * @return Response
	 */
	public function indexCreativityProposalAction()
	{
		$deleteFormCollection = array();
		$creativityProposalManager = $this->container->get('webapp.manager.creativity_proposal_manager');

		$creativityProposalCollection = $creativityProposalManager->getBy(array());
		foreach ($creativityProposalCollection as $creativityProposal) {
			if($creativityProposal instanceof CreativityProposal){
				$deleteFormCollection[$creativityProposal->getId()] = $this->createDeleteForm($creativityProposal)->createView();
			}
		}

		return $this->render(
			'AppBundle:CreativityProposal:list.html.twig',
			array(
				'creativityProposalCollection' => $creativityProposalCollection,
				'deleteFormCollection' => $deleteFormCollection,
				'breadcrumbs' => $this->getBreadCrumbs(false),
				'active_side_bar' => $this->getActiveSidebar()
			)
		);
	}

	/**
	 * Create creativity proposal entity.
	 *
	 * @param Request $request
	 *
	 * @Route("/create", name="admin_creativity_proposal_create")
	 * @Method({"GET", "POST"})
	 * @Security("has_role('ROLE_CLIENT')")
	 * @return Response
	 */
	public function createCreativityProposalAction(Request $request)
	{
		$entity = new CreativityProposal();
		/** @var Client $client */
		$client = $this->container->get('security.token_storage')->getToken()->getUser();
		$form = $this->createForm('AppBundle\Form\CreativityProposalType', $entity);
		$form->add('submit', 'Symfony\Component\Form\Extension\Core\Type\SubmitType', array('label' => $this->get('translator')->trans('app.create_btn'),'attr'=>array('class'=>'btn btn-success')));
		$form->handleRequest($request);

		if ($form->isSubmitted() && $form->isValid()) {
			$em = $this->getDoctrine()->getManager();
			$em->persist($entity);
			$em->flush();

			//Send Email
			$this->get('event_dispatcher')->dispatch(
				ProposalEvents::PROPOSAL_CREATED,
				new ProposalEvent($entity)
			);

			$this->get('session')->getFlashBag()->add('success', $this->get('translator')->trans('creativity_proposal.create_succesfull'));

			return $this->redirectToRoute('admin_creativity_proposal_list');
		}

		return $this->render('AppBundle:CreativityProposal:new.html.twig', array(
			'entity' => $entity,
			'form' => $form->createView(),
			'latitude' => $client->getLatitude(),
			'longitude' => $client->getLongitude(),
			'breadcrumbs' => ($this->isGranted('ROLE_ADMIN')) ? $this->getBreadCrumbs(true, array("name" => "backend.create")) : $this->getBreadCrumbs(false, array("name" => "backend.create")),
			'active_side_bar' => $this->getActiveSidebar()
		));
	}

	/**
	 * Displays a show view to an existing creatvitiy proposal entity.
	 *
	 * @param Request $request
	 * @param CreativityProposal $entity
	 *
	 * @Route("/{id}/edit", name="admin_creativity_proposal_show")
	 * @Method({"GET", "POST"})
	 * @Security("has_role('ROLE_ADMIN')")
	 *
	 * @return \Symfony\Component\HttpFoundation\RedirectResponse|Response
	 */
	public function showCreativityProposalAction(Request $request, CreativityProposal $entity)
	{
		return $this->render('AppBundle:CreativityProposal:show.html.twig', array(
			'entity' => $entity,
			'breadcrumbs' => $this->getBreadCrumbs(true, array("name" => "backend.show")),
			'active_side_bar' => $this->getActiveSidebar()
		));
	}

	/**
	 * Deletes a creativity proposal entity.
	 *
	 * @param Request $request
	 * @param CreativityProposal  $entity
	 *
	 * @Route("/{id}", name="admin_creativity_proposal_delete")
	 * @Method("DELETE")
	 * @Security("has_role('ROLE_ADMIN')")
	 *
	 * @return \Symfony\Component\HttpFoundation\RedirectResponse
	 */
	public function deleteCreativityProposalAction(Request $request, CreativityProposal $entity)
	{
		$form = $this->createDeleteForm($entity);
		$form->handleRequest($request);

		if ($form->isSubmitted() && $form->isValid()) {
			$em = $this->getDoctrine()->getManager();
			$em->remove($entity);
			$em->flush();
			$this->get('session')->getFlashBag()->add('success', $this->get('translator')->trans('creativity_proposal.delete_succesfull'));
		}

		return $this->redirectToRoute('admin_creativity_proposal_list');
	}

	/**
	 * Creates a form to delete a creativity proposal entity.
	 *
	 * @param CreativityProposal $entity
	 *
	 * @return \Symfony\Component\Form\Form The form
	 */
	private function createDeleteForm(CreativityProposal $entity)
	{
		return $this->createFormBuilder()
			->add('submit', 'Symfony\Component\Form\Extension\Core\Type\SubmitType', array('label' =>'app.delete', 'attr' => array('class' => 'btn btn-danger')))
			->setAction($this->generateUrl('admin_creativity_proposal_delete', array('id' => $entity->getId())))
			->setMethod('DELETE')
			->getForm()
			;
	}
}