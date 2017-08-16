<?php


namespace AppBundle\Controller;

use AppBundle\Entity\MultimediaCategory;
use AppBundle\Entity\Multimedia;
use AppBundle\Model\MultimediaModel;
use Doctrine\Common\Collections\ArrayCollection;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Method;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Security;
use Symfony\Component\Form\Form;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use BackendBundle\Controller\DefaultController as BackendBundleController;

/**
 * Class MultimediaController
 * @Route("/multimedia")
 */
class MultimediaController extends BackendBundleController
{
	protected $activeSideBar = 'multimedia';

	protected function getBreadCrumbs($url = null, $data = array())
	{
		$breadcrumbs = parent::getBreadCrumbs($url);

		$breadcrumbs[] = array(
			'name' => 'multimedia.title_nav'
		);

		if($url){
			$breadcrumbs[1]['url'] = $this->generateUrl('admin_multimedia_list');
		}

		if($data && is_array($data)){
			$breadcrumbs[] = $data;
		}

		return $breadcrumbs;
	}

	/**
	 * List multimedia entities
	 *
	 * @param Request $request
	 *
	 * @Route("/list", name="admin_multimedia_list")
	 * @Method({"GET","POST"})
	 * @Security("has_role('ROLE_CLIENT')")
	 * @return Response
	 */
	public function indexMultimediaAction(Request $request)
	{
		$deleteFormCollection = array();
		$categoryMultimedia = $this->getCategoryMultimedia($request);

		$multimediaManager = $this->container->get('webapp.manager.multimedia_manager');
		$multimediaCollection = $this->getQuery($categoryMultimedia, $multimediaManager);
		$filterForm = $this->get('webapp.form.filter_multimedia_category')->buildForm($categoryMultimedia);


		$pagination = $this->get('knp_paginator')->paginate(
			$multimediaCollection,
			$request->query->getInt('page', 1)/*page number*/,
			12/*limit per page*/
		);

		$deleteFormCollection = $this->getDeleteFormCollection($multimediaCollection, $deleteFormCollection);

		return $this->render(
			'AppBundle:Multimedia:list.html.twig',
			array(
				'multimediaCollection' => $multimediaCollection,
				'deleteFormCollection' => $deleteFormCollection,
				'filterForm' => $filterForm->createView(),
				'pagination' => $pagination,
				'breadcrumbs' => $this->getBreadCrumbs(false),
				'active_side_bar' => $this->getActiveSidebar()
			)
		);
	}

	private function getCategoryMultimedia(Request $request)
	{
		$form = $request->query->get('form');
		$categoryMultimedia = null;
		if(!empty($form)){
			$categoryMultimedia = (array_key_exists('multimedia-category', $form)) ? $form['multimedia-category'] : null ;
		}

		return $categoryMultimedia;
	}

	/**
	 * @param $categoryMultimedia
	 * @param MultimediaModel $multimediaManager
	 *
	 * @return ArrayCollection
	 */
	private function getQuery($categoryMultimedia, MultimediaModel $multimediaManager)
	{
		if (!empty($categoryMultimedia) && $categoryMultimedia != -1) {
			$multimediaCollection = $multimediaManager->getBy(array('category' => $categoryMultimedia));
		} else {
			$multimediaCollection = $multimediaManager->getBy(array());
		}

		return $multimediaCollection;
	}

	/**
	 * @param $multimediaCollection
	 * @param $deleteFormCollection
	 *
	 * @return mixed
	 */
	private function getDeleteFormCollection($multimediaCollection, $deleteFormCollection)
	{
		foreach ($multimediaCollection as $multimedia) {
			if ($multimedia instanceof Multimedia) {
				$deleteFormCollection[$multimedia->getId()] = $this->createDeleteForm($multimedia)->createView();
			}
		}

		return $deleteFormCollection;
	}

	/**
	 * Create multimedia entity.
	 *
	 * @param Request $request
	 *
	 * @Route("/create", name="admin_multimedia_create")
	 * @Method({"GET", "POST"})
	 * @Security("has_role('ROLE_ADMIN')")
	 * @return Response
	 */
	public function createMultimediaAction(Request $request)
	{
		$entity = new Multimedia();
		$form = $this->createForm('AppBundle\Form\MultimediaType', $entity);
		$form->add('submit', 'Symfony\Component\Form\Extension\Core\Type\SubmitType', array('label' => $this->get('translator')->trans('app.create_btn'),'attr'=>array('class'=>'btn btn-success')));
		$form->handleRequest($request);

		if ($form->isSubmitted() && $form->isValid()) {
			$em = $this->getDoctrine()->getManager();
			$em->persist($entity);
			$em->flush();

			$this->get('session')->getFlashBag()->add('success', $this->get('translator')->trans('client.create_succesfull'));

			return $this->redirectToRoute('admin_multimedia_list');
		}

		return $this->render('AppBundle:Multimedia:new.html.twig', array(
			'entity' => $entity,
			'form' => $form->createView(),
			'breadcrumbs' => $this->getBreadCrumbs(true, array("name" => "backend.create")),
			'active_side_bar' => $this->getActiveSidebar()
		));
	}

	/**
	 * Displays a form to edit an existing multimedia entity.
	 *
	 * @param Request $request
	 * @param Multimedia $entity
	 *
	 * @Route("/{id}/edit", name="admin_multimedia_edit")
	 * @Method({"GET", "POST"})
	 * @Security("has_role('ROLE_ADMIN')")
	 *
	 * @return \Symfony\Component\HttpFoundation\RedirectResponse|Response
	 */
	public function editMultimediaAction(Request $request, Multimedia $entity)
	{
		$editForm = $this->createForm('AppBundle\Form\MultimediaType', $entity);
		$editForm->add('submit', 'Symfony\Component\Form\Extension\Core\Type\SubmitType', array('label' => $this->get('translator')->trans('app.edit_btn'),'attr'=>array('class'=>'btn btn-success')));
		$editForm->handleRequest($request);

		if ($editForm->isSubmitted() && $editForm->isValid()) {
			$em = $this->getDoctrine()->getManager();
			$em->persist($entity);
			$em->flush();

			$this->get('session')->getFlashBag()->add('success', $this->get('translator')->trans('client.edit_succesfull'));

			return $this->redirectToRoute('admin_multimedia_edit', array('id' => $entity->getId()));
		}

		return $this->render('AppBundle:Multimedia:edit.html.twig', array(
			'entity' => $entity,
			'form' => $editForm->createView(),
			'breadcrumbs' => $this->getBreadCrumbs(true, array("name" => "backend.edit")),
			'active_side_bar' => $this->getActiveSidebar()
		));
	}

	/**
	 * Deletes a multimedia entity.
	 *
	 * @param Request $request
	 * @param Multimedia  $entity
	 *
	 * @Route("/{id}", name="admin_multimedia_delete")
	 * @Method("DELETE")
	 * @Security("has_role('ROLE_ADMIN')")
	 *
	 * @return \Symfony\Component\HttpFoundation\RedirectResponse
	 */
	public function deleteMultimediaAction(Request $request, Multimedia $entity)
	{
		$form = $this->createDeleteForm($entity);
		$form->handleRequest($request);

		if ($form->isSubmitted() && $form->isValid()) {
			$em = $this->getDoctrine()->getManager();
			$em->remove($entity);
			$em->flush();
			$this->get('session')->getFlashBag()->add('success', $this->get('translator')->trans('multimedia.delete_succesfull'));
		}

		return $this->redirectToRoute('admin_multimedia_list');
	}

	/**
	 * Creates a form to delete a multimedia entity.
	 *
	 * @param Multimedia $entity
	 *
	 * @return \Symfony\Component\Form\Form The form
	 */
	private function createDeleteForm(Multimedia $entity)
	{
		return $this->createFormBuilder()
			->add('submit', 'Symfony\Component\Form\Extension\Core\Type\SubmitType', array('label' =>'app.delete', 'attr' => array('class' => 'btn btn-danger')))
			->setAction($this->generateUrl('admin_multimedia_delete', array('id' => $entity->getId())))
			->setMethod('DELETE')
			->getForm()
			;
	}
}