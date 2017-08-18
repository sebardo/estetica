<?php


namespace AppBundle\Controller;

use AppBundle\Entity\AcademicStudy;
use AppBundle\Model\AcademicModel;
use Doctrine\Common\Collections\ArrayCollection;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Method;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Security;
use Symfony\Component\Form\Form;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use BackendBundle\Controller\DefaultController as BackendBundleController;


/**
 * Class AcademicController
 * @Route("/academic")
 */
class AcademicController extends BackendBundleController
{
	protected $activeSideBar = 'academic';

	protected function getBreadCrumbs($url = null, $data = array())
	{
		$breadcrumbs = parent::getBreadCrumbs($url);

		$breadcrumbs[] = array(
			'name' => 'academic.title_nav'
		);

		if($url){
			$breadcrumbs[1]['url'] = $this->generateUrl('admin_academic_list');
		}

		if($data && is_array($data)){
			$breadcrumbs[] = $data;
		}

		return $breadcrumbs;
	}

	/**
	 * List academic studies entities
	 *
	 * @Route("/list", name="admin_academic_list")
	 * @Method({"GET","POST"})
	 * @Security("has_role('ROLE_CLIENT')")
	 * @return Response
	 */
	public function indexAcademicStudyAction()
	{
		$deleteFormCollection = array();

		$academicStudyManager = $this->container->get('webapp.manager.academic_manager');
		$academicStudyCollection = $academicStudyManager->getBy(array());

		$deleteFormCollection = $this->getDeleteFormCollection($academicStudyCollection, $deleteFormCollection);

		return $this->render(
			'AppBundle:Academic:list.html.twig',
			array(
				'academicCollection' => $academicStudyCollection,
				'deleteFormCollection' => $deleteFormCollection,
				'breadcrumbs' => $this->getBreadCrumbs(false),
				'active_side_bar' => $this->getActiveSidebar()
			)
		);
	}

	/**
	 * @param $academicCollection
	 * @param $deleteFormCollection
	 *
	 * @return mixed
	 */
	private function getDeleteFormCollection($academicCollection, $deleteFormCollection)
	{
		foreach ($academicCollection as $academic) {
			if ($academic instanceof AcademicStudy) {
				$deleteFormCollection[$academic->getId()] = $this->createDeleteForm($academic)->createView();
			}
		}

		return $deleteFormCollection;
	}

	/**
	 * Create academic study entity.
	 *
	 * @param Request $request
	 *
	 * @Route("/create", name="admin_academic_create")
	 * @Method({"GET", "POST"})
	 * @Security("has_role('ROLE_ADMIN')")
	 * @return Response
	 */
	public function createAcademicStudyAction(Request $request)
	{
		$entity = new AcademicStudy();
		$form = $this->createForm('AppBundle\Form\AcademicStudyType', $entity);
		$form->add('submit', 'Symfony\Component\Form\Extension\Core\Type\SubmitType', array('label' => $this->get('translator')->trans('app.create_btn'),'attr'=>array('class'=>'btn btn-success')));
		$form->handleRequest($request);

		if ($form->isSubmitted() && $form->isValid()) {
			$em = $this->getDoctrine()->getManager();
			$em->persist($entity);
			$em->flush();

			$this->get('session')->getFlashBag()->add('success', $this->get('translator')->trans('academic.create_succesfull'));

			return $this->redirectToRoute('admin_academic_list');
		}

		return $this->render('AppBundle:Academic:new.html.twig', array(
			'entity' => $entity,
			'form' => $form->createView(),
			'breadcrumbs' => $this->getBreadCrumbs(true, array("name" => "backend.create")),
			'active_side_bar' => $this->getActiveSidebar()
		));
	}

	/**
	 * Displays a form to edit an existing academic study entity.
	 *
	 * @param Request $request
	 * @param AcademicStudy $entity
	 *
	 * @Route("/{id}/edit", name="admin_academic_edit")
	 * @Method({"GET", "POST"})
	 * @Security("has_role('ROLE_ADMIN')")
	 *
	 * @return \Symfony\Component\HttpFoundation\RedirectResponse|Response
	 */
	public function editAcademicStudyAction(Request $request, AcademicStudy $entity)
	{
		$editForm = $this->createForm('AppBundle\Form\AcademicStudyType', $entity);
		$editForm->add('submit', 'Symfony\Component\Form\Extension\Core\Type\SubmitType', array('label' => $this->get('translator')->trans('app.edit_btn'),'attr'=>array('class'=>'btn btn-success')));
		$editForm->handleRequest($request);

		if ($editForm->isSubmitted() && $editForm->isValid()) {
			$em = $this->getDoctrine()->getManager();
			$em->persist($entity);
			$em->flush();

			$this->get('session')->getFlashBag()->add('success', $this->get('translator')->trans('academic.edit_succesfull'));

			return $this->redirectToRoute('admin_academic_edit', array('id' => $entity->getId()));
		}

		return $this->render('AppBundle:Academic:edit.html.twig', array(
			'entity' => $entity,
			'form' => $editForm->createView(),
			'breadcrumbs' => $this->getBreadCrumbs(true, array("name" => "backend.edit")),
			'active_side_bar' => $this->getActiveSidebar()
		));
	}

	/**
	 * Deletes a academic study entity.
	 *
	 * @param Request $request
	 * @param AcademicStudy  $entity
	 *
	 * @Route("/{id}", name="admin_academic_delete")
	 * @Method("DELETE")
	 * @Security("has_role('ROLE_ADMIN')")
	 *
	 * @return \Symfony\Component\HttpFoundation\RedirectResponse
	 */
	public function deleteAcademicStudyAction(Request $request, AcademicStudy $entity)
	{
		$form = $this->createDeleteForm($entity);
		$form->handleRequest($request);

		if ($form->isSubmitted() && $form->isValid()) {
			$em = $this->getDoctrine()->getManager();
			$em->remove($entity);
			$em->flush();
			$this->get('session')->getFlashBag()->add('success', $this->get('translator')->trans('academic.delete_succesfull'));
		}

		return $this->redirectToRoute('admin_academic_list');
	}

	/**
	 * Creates a form to delete a academic study entity.
	 *
	 * @param AcademicStudy $entity
	 *
	 * @return \Symfony\Component\Form\Form The form
	 */
	private function createDeleteForm(AcademicStudy $entity)
	{
		return $this->createFormBuilder()
			->add('submit', 'Symfony\Component\Form\Extension\Core\Type\SubmitType', array('label' =>'app.delete', 'attr' => array('class' => 'btn btn-danger')))
			->setAction($this->generateUrl('admin_academic_delete', array('id' => $entity->getId())))
			->setMethod('DELETE')
			->getForm()
			;
	}
}