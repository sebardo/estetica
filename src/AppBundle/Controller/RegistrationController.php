<?php


namespace AppBundle\Controller;

use AppBundle\Entity\Registration;
use AppBundle\Form\RegistrationType;
use AppBundle\Model\RegistrationModel;
use Doctrine\Common\Collections\ArrayCollection;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Method;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Security;
use Symfony\Component\Form\Form;
use Symfony\Component\HttpFoundation\File\File;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use BackendBundle\Controller\DefaultController as BackendBundleController;

/**
 * Class AcademicController
 * @Route("/")
 */
class RegistrationController extends BackendBundleController
{
	protected $activeSideBar = 'registration';

	protected function getBreadCrumbs($url = null, $data = array())
	{
		$breadcrumbs = parent::getBreadCrumbs($url);

		$breadcrumbs[] = array(
			'name' => 'registration.title_nav'
		);

		if($url){
			$breadcrumbs[1]['url'] = $this->generateUrl('admin_registration_list');
		}

		if($data && is_array($data)){
			$breadcrumbs[] = $data;
		}

		return $breadcrumbs;
	}

	/**
	 * List registration entities
	 *
	 * @param Request $request
	 *
	 * @Route("/admin/registration/list", name="admin_registration_list")
	 * @Method({"GET","POST"})
	 * @Security("has_role('ROLE_CLIENT')")
	 * @return Response
	 */
	public function indexRegistrationAction(Request $request)
	{
		$deleteFormCollection = array();
		$globalDataForm = $request->query->all();
		$dataForm = (array_key_exists('form', $globalDataForm)) ? $globalDataForm['form'] : null;

		$registrationManager = $this->container->get('webapp.manager.registration_manager');
		$registrationCollection = $this->getQuery($dataForm, $registrationManager);
		$filterForm = $this->get('webapp.form.filter_registration')->buildForm($dataForm);

		$pagination = $this->get('knp_paginator')->paginate(
			$registrationCollection,
			$request->query->getInt('page', 1)/*page number*/,
			12/*limit per page*/
		);

		$deleteFormCollection = $this->getDeleteFormCollection($pagination, $deleteFormCollection);

		return $this->render(
			'AppBundle:Registration:list.html.twig',
			array(
				'registrationCollection' => $registrationCollection,
				'deleteFormCollection' => $deleteFormCollection,
				'filterForm' => $filterForm->createView(),
				'pagination' => $pagination,
				'breadcrumbs' => $this->getBreadCrumbs(false),
				'active_side_bar' => $this->getActiveSidebar()
			)
		);
	}

	private function getQuery($dataForm, RegistrationModel $registrationManager)
	{
		return $registrationManager->getQueryByFilterForm($dataForm);
	}

	/**
	 * @param $registrationCollection
	 * @param $deleteFormCollection
	 *
	 * @return mixed
	 */
	private function getDeleteFormCollection($registrationCollection, $deleteFormCollection)
	{
		foreach ($registrationCollection as $registration) {
			if ($registration instanceof Registration) {
				$deleteFormCollection[$registration->getId()] = $this->createDeleteForm($registration)->createView();
			}
		}

		return $deleteFormCollection;
	}

	/**
	 * Create registration entity.
	 *
	 * @param Request $request
	 *
	 * @Route("/admin/registration/create", name="admin_registration_create")
	 * @Method({"GET", "POST"})
	 * @Security("has_role('ROLE_ADMIN')")
	 * @return Response
	 */
	public function createRegistrationAction(Request $request)
	{
		$entity = new Registration();
		$form = $this->createForm(new RegistrationType($this->container), $entity, array('edit_form' => false));
		$form->add('submit', 'Symfony\Component\Form\Extension\Core\Type\SubmitType', array('label' => $this->get('translator')->trans('app.create_btn'),'attr'=>array('class'=>'btn btn-success')));
		$form->handleRequest($request);

		if ($form->isSubmitted() && $form->isValid()) {
			$this->get('webapp.manager.registration_manager')->create($entity);

			$this->get('session')->getFlashBag()->add('success', $this->get('translator')->trans('registration.create_succesfull'));

			return $this->redirectToRoute('admin_registration_list');
		}

		return $this->render('AppBundle:Registration:new.html.twig', array(
			'entity' => $entity,
			'form' => $form->createView(),
			'breadcrumbs' => $this->getBreadCrumbs(true, array("name" => "backend.create")),
			'active_side_bar' => $this->getActiveSidebar()
		));
	}

	/**
	 * Displays a form to edit an existing registration entity.
	 *
	 * @param Request $request
	 * @param Registration $entity
	 *
	 * @Route("/admin/registration/{id}/edit", name="admin_registration_edit")
	 * @Method({"GET", "POST"})
	 * @Security("has_role('ROLE_ADMIN')")
	 *
	 * @return \Symfony\Component\HttpFoundation\RedirectResponse|Response
	 */
	public function editRegistrationAction(Request $request, Registration $entity)
	{
		$editForm = $this->createForm(new RegistrationType($this->container), $entity, array('edit_form' => true));
		$editForm->add('submit', 'Symfony\Component\Form\Extension\Core\Type\SubmitType', array('label' => $this->get('translator')->trans('app.save_btn'),'attr'=>array('class'=>'btn btn-success')));
		$editForm->handleRequest($request);

		if ($editForm->isSubmitted() && $editForm->isValid()) {
			$em = $this->getDoctrine()->getManager();
			$em->persist($entity);
			$em->flush();

			$this->get('session')->getFlashBag()->add('success', $this->get('translator')->trans('registration.edit_succesfull'));

			return $this->redirectToRoute('admin_registration_edit', array('id' => $entity->getId()));
		}

		return $this->render('AppBundle:Registration:edit.html.twig', array(
			'entity' => $entity,
			'form' => $editForm->createView(),
			'breadcrumbs' => $this->getBreadCrumbs(true, array("name" => "backend.edit")),
			'active_side_bar' => $this->getActiveSidebar()
		));
	}

	/**
	 * Displays a show view to an existing press release entity.
	 *
	 * @param Request $request
	 * @param Registration $entity
	 *
	 * @Route("/admin/registration/{id}/show", name="admin_registration_show")
	 * @Method({"GET", "POST"})
	 * @Security("has_role('ROLE_CLIENT')")
	 *
	 * @return \Symfony\Component\HttpFoundation\RedirectResponse|Response
	 */
	public function showRegistrationAction(Request $request, Registration $entity)
	{
		$editForm = $this->createForm(new RegistrationType($this->container), $entity, array('edit_form' => true));
		$editForm->add('submit', 'Symfony\Component\Form\Extension\Core\Type\SubmitType', array('label' => $this->get('translator')->trans('app.edit_btn'),'attr'=>array('class'=>'btn btn-success')));

		return $this->render('AppBundle:Registration:show.html.twig', array(
			'entity' => $entity,
			'form' => $editForm->createView(),
			'breadcrumbs' => $this->getBreadCrumbs(true, array("name" => "app.show")),
			'active_side_bar' => $this->getActiveSidebar()
		));
	}

	/**
	 * Deletes a registration entity.
	 *
	 * @param Request $request
	 * @param Registration  $entity
	 *
	 * @Route("/admin/registration/{id}", name="admin_registration_delete")
	 * @Method("DELETE")
	 * @Security("has_role('ROLE_ADMIN')")
	 *
	 * @return \Symfony\Component\HttpFoundation\RedirectResponse
	 */
	public function deleteRegistrationAction(Request $request, Registration $entity)
	{
		$form = $this->createDeleteForm($entity);
		$form->handleRequest($request);

		if ($form->isSubmitted() && $form->isValid()) {
			$em = $this->getDoctrine()->getManager();
			$em->remove($entity);
			$em->flush();
			$this->get('session')->getFlashBag()->add('success', $this->get('translator')->trans('registration.delete_succesfull'));
		}

		return $this->redirectToRoute('admin_registration_list');
	}

	/**
	 * Creates a form to delete a registration entity.
	 *
	 * @param Registration $entity
	 *
	 * @return \Symfony\Component\Form\Form The form
	 */
	private function createDeleteForm(Registration $entity)
	{
		return $this->createFormBuilder()
			->add('submit', 'Symfony\Component\Form\Extension\Core\Type\SubmitType', array('label' =>'app.delete', 'attr' => array('class' => 'btn btn-danger')))
			->setAction($this->generateUrl('admin_registration_delete', array('id' => $entity->getId())))
			->setMethod('DELETE')
			->getForm()
			;
	}
}