<?php


namespace AppBundle\Controller;

use AppBundle\Entity\Creativity;
use AppBundle\Entity\CreativityFileRaw;
use Doctrine\Common\Persistence\ObjectManager;
use Doctrine\ORM\EntityManager;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Method;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Security;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use BackendBundle\Controller\DefaultController as BackendBundleController;

/**
 * Class CreativityController
 * @Route("/admin/creativity")
 */
class CreativityController extends BackendBundleController
{
	protected $activeSideBar = 'creativity';

	protected function getBreadCrumbs($url = null, $data = array())
	{
		$breadcrumbs = parent::getBreadCrumbs($url);

		$breadcrumbs[] = array(
			'name' => 'creativity.title_nav'
		);

		if($url){
			$breadcrumbs[1]['url'] = $this->generateUrl('admin_creativity_list');
		}

		if($data && is_array($data)){
			$breadcrumbs[] = $data;
		}

		return $breadcrumbs;
	}

	/**
	 * List creativity entities
	 *
	 * @Route("/list", name="admin_creativity_list")
	 * @Method({"GET","POST"})
	 * @Security("has_role('ROLE_ADMIN')")
	 * @return Response
	 */
	public function indexCreativityAction()
	{
		$deleteFormCollection = array();
		$creativityManager = $this->container->get('webapp.manager.creativity_manager');

		$creativityCollection = $creativityManager->getBy(array());
		foreach ($creativityCollection as $creativity) {
			if($creativity instanceof Creativity){
				$deleteFormCollection[$creativity->getId()] = $this->createDeleteForm($creativity)->createView();
			}
		}

		return $this->render(
			'AppBundle:Creativity:list.html.twig',
			array(
				'creativityCollection' => $creativityCollection,
				'deleteFormCollection' => $deleteFormCollection,
				'breadcrumbs' => $this->getBreadCrumbs(false),
				'active_side_bar' => $this->getActiveSidebar()
			)
		);
	}

	/**
	 * Create creativity entity.
	 *
	 * @param Request $request
	 *
	 * @Route("/create", name="admin_creativity_create")
	 * @Method({"GET", "POST"})
	 * @Security("has_role('ROLE_ADMIN')")
	 * @return Response
	 */
	public function createCreativityAction(Request $request)
	{
		$entity = new Creativity();
		$form = $this->createForm('AppBundle\Form\CreativityType', $entity);
		$form->add('submit', 'Symfony\Component\Form\Extension\Core\Type\SubmitType', array('label' => $this->get('translator')->trans('app.create_btn'),'attr'=>array('class'=>'btn btn-success')));
		$form->handleRequest($request);

		if ($form->isSubmitted() && $form->isValid()) {
			/** @var ObjectManager $em */
			$em = $this->getDoctrine()->getManager();
//			$this->settingFileDocRawRelationship($request, $em, $entity);
			$em->persist($entity);
			$em->flush();

			$this->get('session')->getFlashBag()->add('success', $this->get('translator')->trans('creativity.create_succesfull'));

			return $this->redirectToRoute('admin_creativity_list');
		}

		return $this->render('AppBundle:Creativity:new.html.twig', array(
			'entity' => $entity,
			'form' => $form->createView(),
			'breadcrumbs' => $this->getBreadCrumbs(true, array("name" => "backend.create")),
			'active_side_bar' => $this->getActiveSidebar()
		));
	}

	/**
	 * Displays a form to edit an existing creativity entity.
	 *
	 * @param Request $request
	 * @param Creativity $entity
	 *
	 * @Route("/{id}/edit", name="admin_creativity_edit")
	 * @Method({"GET", "POST"})
	 * @Security("has_role('ROLE_ADMIN')")
	 *
	 * @return \Symfony\Component\HttpFoundation\RedirectResponse|Response
	 */
	public function editCreativityAction(Request $request, Creativity $entity)
	{
		$editForm = $this->createForm('AppBundle\Form\CreativityType', $entity, array('edit_form' => true));
		$editForm->add('submit', 'Symfony\Component\Form\Extension\Core\Type\SubmitType', array('label' => $this->get('translator')->trans('app.edit_btn'),'attr'=>array('class'=>'btn btn-success')));
		$editForm->handleRequest($request);

		if ($editForm->isSubmitted() && $editForm->isValid()) {
			$em = $this->getDoctrine()->getManager();
//			$this->settingFileDocRawRelationship($request, $em, $entity);
			$em->persist($entity);
			$em->flush();

			$this->get('session')->getFlashBag()->add('success', $this->get('translator')->trans('creativity.edit_succesfull'));

			return $this->redirectToRoute('admin_creativity_edit', array('id' => $entity->getId()));
		}

		return $this->render('AppBundle:Creativity:edit.html.twig', array(
			'entity' => $entity,
			'form' => $editForm->createView(),
			'breadcrumbs' => $this->getBreadCrumbs(true, array("name" => "backend.edit")),
			'active_side_bar' => $this->getActiveSidebar()
		));
	}

	/**
	 * Deletes a creativity entity.
	 *
	 * @param Request $request
	 * @param Creativity  $entity
	 *
	 * @Route("/{id}", name="admin_creativity_delete")
	 * @Method("DELETE")
	 * @Security("has_role('ROLE_ADMIN')")
	 *
	 * @return \Symfony\Component\HttpFoundation\RedirectResponse
	 */
	public function deleteCreativityAction(Request $request, Creativity $entity)
	{
		$form = $this->createDeleteForm($entity);
		$form->handleRequest($request);

		if ($form->isSubmitted() && $form->isValid()) {
			$em = $this->getDoctrine()->getManager();
			$em->remove($entity);
			$em->flush();
			$this->get('session')->getFlashBag()->add('success', $this->get('translator')->trans('creativity.delete_succesfull'));
		}

		return $this->redirectToRoute('admin_creativity_list');
	}

	/**
	 * Creates a form to delete a creativity entity.
	 *
	 * @param Creativity $entity
	 *
	 * @return \Symfony\Component\Form\Form The form
	 */
	private function createDeleteForm(Creativity $entity)
	{
		return $this->createFormBuilder()
			->add('submit', 'Symfony\Component\Form\Extension\Core\Type\SubmitType', array('label' =>'app.delete', 'attr' => array('class' => 'btn btn-danger')))
			->setAction($this->generateUrl('admin_creativity_delete', array('id' => $entity->getId())))
			->setMethod('DELETE')
			->getForm()
			;
	}

	/**
	 * @param Request       $request
	 * @param ObjectManager $em
	 * @param Creativity    $entity
	 */
	private function settingFileDocRawRelationship(Request $request, ObjectManager $em, Creativity $entity)
	{
		$files = $request->files->all();
		$formFilesRaw = $files['appbundle_creativity']['fileDocsRaw'];
		foreach ($formFilesRaw as $fileDocRaw) {
			$file = $fileDocRaw['fileVich']['file'];
			$originalName = $file->getClientOriginalName();
			/** @var CreativityFileRaw $lastFileRaw */
			$lastFileRaw = $em->getRepository('AppBundle:CreativityFileRaw')->findOneBy(array('file' => $originalName, 'creativity' => null), array('createdAt' => 'DESC'));
			if (! empty($lastFileRaw)) {
				$lastFileRaw->setCreativity($entity);
				$em->persist($lastFileRaw);
			}
		}
	}
}