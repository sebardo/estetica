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
use AppBundle\Entity\RegistrationHasSpeciality;

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
                        
                    $this->get('session')->set('registration_post', $request->request->get('appbundle_registration'));
                            
                    return $this->redirectToRoute('admin_registration_create2');
 
		}

		return $this->render('AppBundle:Registration:new.html.twig', array(
			'entity' => $entity,
			'form' => $form->createView(),
			'breadcrumbs' => $this->getBreadCrumbs(true, array("name" => "backend.create")),
			'active_side_bar' => $this->getActiveSidebar()
		));
	}
        
        
        /**
	 * Create registration entity.
	 *
	 * @param Request $request
	 *
	 * @Route("/admin/registration/create2", name="admin_registration_create2")
	 * @Method({"GET", "POST"})
	 * @Security("has_role('ROLE_ADMIN')")
	 * @return Response
	 */
	public function createRegistration2Action(Request $request)
	{
            if($this->get('session')->has('registration_post')){
                $post = $this->get('session')->get('registration_post');
		$entity = new Registration();
                $this->hydrateEntity($entity, $post);
		$form = $this->createForm(new RegistrationType($this->container), $entity, array('edit_form' => false, 'post' => $post));
		$form->add('submit', 'Symfony\Component\Form\Extension\Core\Type\SubmitType', array('label' => $this->get('translator')->trans('app.create_btn'),'attr'=>array('class'=>'btn btn-success')));
		$form->handleRequest($request);

		if ($form->isSubmitted() && $form->isValid()) {
                                                    
                    $this->get('webapp.manager.registration_manager')->create($entity);

                    $this->get('session')->getFlashBag()->add('success', $this->get('translator')->trans('registration.create_succesfull'));

                    $this->get('session')->set('registration_post', ''); 
                             
                    return $this->redirectToRoute('admin_registration_list');
		}

		return $this->render('AppBundle:Registration:new.html.twig', array(
			'entity' => $entity,
			'form' => $form->createView(),
			'breadcrumbs' => $this->getBreadCrumbs(true, array("name" => "backend.create")),
			'active_side_bar' => $this->getActiveSidebar(),
                        'step2' => true
		)); 
            }
            
	}

        public function hydrateEntity(Registration $entity, $post) 
        {
            $em = $this->get('doctrine')->getManager();
            if(isset($post['name'])) $entity->setName($post['name']);
            if(isset($post['firstLastname'])) $entity->setFirstLastname($post['firstLastname']);
            if(isset($post['secondLastname'])) $entity->setSecondLastname($post['secondLastname']);
            if(isset($post['phone'])) $entity->setPhone($post['phone']);
            if(isset($post['mobile'])) $entity->setMobile($post['mobile']);
            if(isset($post['email'])) $entity->setEmail($post['email']);
            if(isset($post['gender'])) $entity->setGender($post['gender']);
            if(isset($post['birthday'])) $entity->setBirthday(\DateTime::createFromFormat('Y-m-d', $post['birthday']));
            if(isset($post['placeResidence'])){
                $pr = new Registration\PlaceResidence();
                if(isset($post['placeResidence']['address'])) $pr->setAddress($post['placeResidence']['address']);
                if(isset($post['placeResidence']['addressInfo'])) $pr->setAddressInfo($post['placeResidence']['addressInfo']);
                if(isset($post['placeResidence']['postalCode'])) $pr->setPostalCode($post['placeResidence']['postalCode']);
                $city = $em->getRepository('AppBundle:City')->find($post['placeResidence']['city']);
                if(isset($post['placeResidence']['city'])) $pr->setCity($city);
                $entity->setPlaceResidence($pr);
            }
            if(isset($post['certificateDisability'])) $entity->setCertificateDisability($post['certificateDisability']);
            if(isset($post['vehicle'])) $entity->setVehicle($post['vehicle']);
            if(isset($post['travelAvailability'])) $entity->setTravelAvailability($post['travelAvailability']);
            if(isset($post['studies']) && count($post['studies']) > 0){
                foreach ($post['studies'] as $studies) {
                   $study =  $em->getRepository('AppBundle:Registration\Study')->find($studies);
                   $entity->addStudy($study);
                }
 
            }
//            if(isset($post['parentSpeciality'])) {
//                $parent =  $em->getRepository('AppBundle:Registration\Speciality')->find($post['parentSpeciality']);
//                $entity->addRegistrationHasSpeciality($parent);
//            }
       
            if(isset($post['speciality_estetica']) && count($post['speciality_estetica']) > 0){
                foreach ($post['speciality_estetica'] as $key => $language) {
                    $num = str_replace('speciality_', '', $key);
                    if(is_numeric($num)){
                       $registrationHasSpeciality = new RegistrationHasSpeciality();
                       $registrationHasSpeciality->setSpeciality($em->getRepository('AppBundle:Registration\Speciality')->find($num));
                       $registrationHasSpeciality->setValue($post['speciality_estetica'][$key.'_detail']);
                       $registrationHasSpeciality->setRegistration($entity);
                       $entity->addRegistrationHasSpeciality($registrationHasSpeciality);
                   }
                }
            }
            
            if(isset($post['course']) && count($post['course']) > 0){
                foreach ($post['course'] as $key => $language) {
                    $num = str_replace('course_', '', $key);
                    if(is_numeric($num)){
                       $registrationHasCourse = new \AppBundle\Entity\RegistrationHasCourse();
                       $registrationHasCourse->setCourse($em->getRepository('AppBundle:Registration\Course')->find($num));
                       $registrationHasCourse->setValue($post['course'][$key.'_detail']);
                       $registrationHasCourse->setRegistration($entity);
                       $entity->addRegistrationHasCourse($registrationHasCourse);
                   }
                }
            }
            
            
            
            if(isset($post['academicStudies']) && count($post['academicStudies']) > 0){
                foreach ($post['academicStudies'] as $as) {
                   $as =  $em->getRepository('AppBundle:AcademicStudy')->find($as);
                   $entity->addAcademicStudy($as);
                }
            }
            if(isset($post['salesTraining'])) $entity->setSalesTraining($post['salesTraining']);
            if(isset($post['language']) && count($post['language']) > 0){
                foreach ($post['language'] as $key => $language) {
                    $num = str_replace('language_', '', $key);
                    if(is_numeric($num)){
                       $registrationHasLanguage = new \AppBundle\Entity\RegistrationHasLanguage();
                       $registrationHasLanguage->setLanguage($em->getRepository('AppBundle:Registration\Language')->find($num));
                       $registrationHasLanguage->setValue($post['language'][$key.'_detail']);
                       $registrationHasLanguage->setRegistration($entity);
                       $entity->addRegistrationHasLanguage($registrationHasLanguage);
                   }
                }
            }
            if(isset($post['experience'])) {
                $exp =  $em->getRepository('AppBundle:Registration\Experience')->find($post['experience']);
                $entity->setExperience($exp);
            }
            if(isset($post['experiencePlaces'])) $entity->setExperiencePlaces($post['experiencePlaces']);
            if(isset($post['contractTypes']) && count($post['contractTypes']) > 0){
                foreach ($post['contractTypes'] as $types) {
                   $types =  $em->getRepository('AppBundle:Registration\TimeAvailability')->find($types);
                   $entity->addContractType($types);
                }
            }
            if(isset($post['timesAvailability']) && count($post['timesAvailability']) > 0){
                foreach ($post['timesAvailability'] as $times) {
                   $times =  $em->getRepository('AppBundle:Registration\TimeAvailability')->find($times);
                   $entity->addTimeAvailability($times);
                }
            }
            if(isset($post['levelsResponsibility']) && count($post['levelsResponsibility']) > 0){
                foreach ($post['levelsResponsibility'] as $level) {
                   $level =  $em->getRepository('AppBundle:Registration\LevelResponsibility')->find($level);
                   $entity->addLevelResponsibility($level);
                }
            }
//            print_r(count($entity->getRegistrationsHaveLanguages()));die;
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