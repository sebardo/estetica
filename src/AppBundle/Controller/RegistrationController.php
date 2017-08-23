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
	 * @Route("/admin/registration/list", name="admin_registration_list")
	 * @Method({"GET","POST"})
	 * @Security("has_role('ROLE_CLIENT')")
	 * @return Response
	 */
	public function indexRegistrationAction()
	{
		$deleteFormCollection = array();

		$registrationManager = $this->container->get('webapp.manager.registration_manager');
		$registrationCollection = $registrationManager->getBy(array());

		$deleteFormCollection = $this->getDeleteFormCollection($registrationCollection, $deleteFormCollection);

		return $this->render(
			'AppBundle:Registration:list.html.twig',
			array(
				'registrationCollection' => $registrationCollection,
				'deleteFormCollection' => $deleteFormCollection,
				'breadcrumbs' => $this->getBreadCrumbs(false),
				'active_side_bar' => $this->getActiveSidebar()
			)
		);
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
		//Delete this
		$entity->setName('Nombre');
		$entity->setFirstLastname('Apellido');
		$entity->setSecondLastname('Apellido2');
		$entity->setPhone('612322121');
		$entity->setMobile('123456789');
		$entity->setEmail('nombre@admin.com');
		$entity->setImage('1.png.jpg');
		$entity->setExperience($this->getDoctrine()->getRepository('AppBundle:Registration\Experience')->findOneBy(array(), array('id' => 'desc')));
		$placeResidence = new Registration\PlaceResidence();
		$placeResidence->setAddress('Direccion');
		$placeResidence->setPostalCode('12345');
		$city = $this->getDoctrine()->getRepository('AppBundle:City')->findOneBy(array(), array('id' => 'desc'));
		$placeResidence->setCity($city);
		$entity->setPlaceResidence($placeResidence);
		//End Delete this
		$form = $this->createForm(new RegistrationType($this->container), $entity);
		$form->add('submit', 'Symfony\Component\Form\Extension\Core\Type\SubmitType', array('label' => $this->get('translator')->trans('app.create_btn'),'attr'=>array('class'=>'btn btn-success')));
		$form->handleRequest($request);

		if ($form->isSubmitted() && $form->isValid()) {
			$em = $this->getDoctrine()->getManager();
			$em->persist($entity);
			$em->flush();

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
		$editForm = $this->createForm(new RegistrationType($this->container), $entity);
		$editForm->add('submit', 'Symfony\Component\Form\Extension\Core\Type\SubmitType', array('label' => $this->get('translator')->trans('app.edit_btn'),'attr'=>array('class'=>'btn btn-success')));
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