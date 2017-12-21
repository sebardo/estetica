<?php

namespace AppBundle\Controller;

use AppBundle\Entity\Client;
use AppBundle\Entity\Creativity;
use AppBundle\Entity\CreativityFile;
use AppBundle\Entity\Registration;
use AppBundle\Entity\Registration\ParentSpeciality;
use AppBundle\Form\RegistrationType;
use AppBundle\Services\Slugify;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Method;
use BackendBundle\Controller\DefaultController as BackendBundleController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use AppBundle\Entity\RegistrationHasSpeciality;

class DefaultController extends BackendBundleController
{
    /**
     * @Route("/", name="homepage")
     */
    public function indexAction(Request $request)
    {
        $user = $this->getUser();
        if(empty($user)){
            return $this->redirectToRoute('login');
        }

        return $this->render('AppBundle:Home:index.html.twig', array(
            'breadcrumbs' => $this->getBreadCrumbs(true, array("name" => "backend.homepage")),
            'active_side_bar' => $this->getActiveSidebar()
        ));
    }

    /**
     * Create registration on front.
     *
     * @param Request $request
     *
     * @Route("/candidatos", name="front_registration_create")
     * @Method({"GET", "POST"})
     * @return Response
     */
    public function createRegistrationAction(Request $request)
    {
        $entity = new Registration();
        $this->hydrateEntity($entity);
        $form = $this->createForm(new RegistrationType($this->container), $entity, array('edit_form' => false));
        $form->add('submit', 'Symfony\Component\Form\Extension\Core\Type\SubmitType', array('label' => $this->get('translator')->trans('app.create_btn'),'attr'=>array('class'=>'btn btn-success')));
        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()) {
            
            if($request->request->has('g-recaptcha-response') && !empty($request->request->get('g-recaptcha-response'))){
                //your site secret key
                $secret = $this->container->getParameter('recaptcha_secret');
                //get verify response data
                $verifyResponse = file_get_contents('https://www.google.com/recaptcha/api/siteverify?secret='.$secret.'&response='.$request->request->get('g-recaptcha-response'));
                $responseData = json_decode($verifyResponse);
                
                if($responseData->success){
                    $this->get('session')->set('registration_post', $request->request->get('appbundle_registration'));
                }else{
                    $this->get('session')->getFlashBag()->add('danger', $this->get('translator')->trans('registration.captcha_fail'));
                }
                return $this->redirectToRoute('front_registration_create2');
            }else{
                $this->get('session')->set('registration_post', $request->request->get('appbundle_registration'));
                $this->get('session')->getFlashBag()->add('danger', $this->get('translator')->trans('registration.captcha_click'));
                return $this->redirectToRoute('front_registration_create');
            }

            
        }

        return $this->render('AppBundle:Registration:front_new.html.twig', array(
            'entity' => $entity,
            'form' => $form->createView(),
            'breadcrumbs' => $this->getBreadCrumbs(true, array("name" => "backend.create")),
            'active_side_bar' => $this->getActiveSidebar()
        ));
    }
    
    
    /**
     * Create registration on front.
     *
     * @param Request $request
     *
     * @Route("/candidatos2", name="front_registration_create2")
     * @Method({"GET", "POST"})
     * @return Response
     */
    public function createRegistration2Action(Request $request)
    {
        $entity = new Registration();
        $this->hydrateEntity($entity);
        $form = $this->createForm(new RegistrationType($this->container), $entity, array('edit_form' => false));
        $form->add('submit', 'Symfony\Component\Form\Extension\Core\Type\SubmitType', array('label' => $this->get('translator')->trans('app.create_btn'),'attr'=>array('class'=>'btn btn-success')));
        $form->handleRequest($request);
        
        if ($form->isSubmitted() && $form->isValid()) {
            $this->container->get("webapp.manager.registration_manager")->create($entity);
            $this->get('session')->getFlashBag()->add('success', $this->get('translator')->trans('registration.create_succesfull'));
            $this->get('session')->set('registration_post', ''); 
            return $this->redirectToRoute('front_registration_create');
        }

        return $this->render('AppBundle:Registration:front_new.html.twig', array(
            'entity' => $entity,
            'form' => $form->createView(),
            'breadcrumbs' => $this->getBreadCrumbs(true, array("name" => "backend.create")),
            'active_side_bar' => $this->getActiveSidebar(),
            'step2' => true
        ));
    }

    /**
     * 
     * @param Registration $entity
     */
    public function hydrateEntity(Registration $entity) 
    {
        if($this->get('session')->has('registration_post')){
            $post = $this->get('session')->get('registration_post');
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
        }

    }

    /**
     * Create client on front.
     *
     * @param Request $request
     *
     * @Route("/registro", name="front_client_create")
     * @Method({"GET", "POST"})
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

            return $this->redirectToRoute('homepage');
        }

        return $this->render('AppBundle:Client:front_new.html.twig', array(
            'entity' => $entity,
            'form' => $form->createView(),
            'breadcrumbs' => $this->getBreadCrumbs(true, array("name" => "backend.create")),
            'active_side_bar' => $this->getActiveSidebar()
        ));
    }

    /**
     * @param Request $request
     *
     * @Route("/ajax/provinces", name="select_provinces")
     * @return JsonResponse
     */
    public function provincesAction(Request $request)
    {
        $response = array();
        $countryId = $request->request->get('country_id');
        $provinceCollection = $this->container->get('webapp.manager.province_manager')->getBy(array("country" => $countryId), array('name' => 'ASC'));

        foreach ($provinceCollection as $province) {
            $response[] = array(
                "id" => $province->getId(),
                "name" => $province->getName()
            );
        }

        return new JsonResponse($response);
    }

    /**
     * @param Request $request
     *
     * @Route("/ajax/cities", name="select_cities")
     * @return JsonResponse
     */
    public function citiesAction(Request $request)
    {
        $response = array();
        $provinceId = $request->request->get('province_id');
        $cityCollection = $this->container->get('webapp.manager.city_manager')->getBy(array("province" => $provinceId), array('name' => 'ASC'));
        foreach ($cityCollection as $city) {
            $response[] = array(
                "id" => $city->getId(),
                "name" => $city->getName()
            );
        }

        return new JsonResponse($response);
    }

    /**
     * @param Request $request
     *
     * @Route("/ajax/language-level", name="select_level_languages")
     * @return JsonResponse
     */
    public function levelLanguagesAction(Request $request)
    {
        $response = array();
        $cont= 0;
        $languagesLevelCollection = $this->getParameter('languages_level');

        foreach ($languagesLevelCollection as $language) {
            $response[] = array(
                "id" => $cont,
                "name" => $language
            );
            $cont++;
        }

        return new JsonResponse($response);
    }

    /**
     * @param Request $request
     *
     * @Route("/ajax/specialities", name="select_parent_specialities")
     * @return JsonResponse
     */
    public function parentSpecialitiesAction(Request $request)
    {
        $response = array();
        $parentSpecialityId = $request->request->get('parent_speciality_id');
        /** @var ParentSpeciality $parentSpeciality */
        $parentSpeciality = $this->container->get('webapp.manager.parent_speciality_manager')->getOneBy(array("id" => $parentSpecialityId));
        $response[] = array(
            "id" => $parentSpeciality->getId(),
            "name" => Slugify::slug($parentSpeciality->getName())
        );

        return new JsonResponse($response);
    }

    /**
     * @param Request $request
     *
     * @Route("/ajax/specialities-children", name="select_specialities")
     * @return JsonResponse
     */
    public function childrenSpecialitiesAction(Request $request)
    {
        $response = array();
        $parentSpecialityId = $request->request->get('parent_speciality_id');
        $specialityCollection = $this->container->get('webapp.manager.speciality_manager')->getBy(array("parent" => $parentSpecialityId));
        foreach ($specialityCollection as $speciality) {
            $response[] = array(
                "id" => $speciality->getId(),
                "name" => $speciality->getName()
            );
        }

        return new JsonResponse($response);
    }

    /**
     * @param Request $request
     *
     * @Route("/ajax/creativity-subcategory", name="select_creativity_subcategories")
     * @return JsonResponse
     */
    public function creativitySubcategoriesAction(Request $request)
    {
        $response = array();
        $categoryId = $request->request->get('category_id');
        $subcategoryCollection = Creativity::getSelectSubcategories($categoryId);

        foreach ($subcategoryCollection as $subategorykey => $subcategoryValue) {
            $response[] = array(
                "id" => $subategorykey,
                "name" => $this->get('translator')->trans($subcategoryValue)
            );
        }

        return new JsonResponse($response);
    }

    /**
     * @param Request $request
     *
     * @Route("/ajax/creativities", name="select_creativities")
     * @return JsonResponse
     */
    public function creativitiesAction(Request $request)
    {
        $response = array();
        $support = $request->request->get('support');
        $category = $request->request->get('category');
        $creativityCollection = $this->get('webapp.manager.creativity_manager')->getBy(array('support' => $support, 'category' => $category), array('category' => 'ASC', 'subcategory' => 'ASC'));

        foreach ($creativityCollection as $creativity) {
            if($creativity instanceof Creativity){
                $image = $creativity->getFileDocs()->first();
                $response[] = array(
                    "id" => $creativity->getId(),
                    "name" => $creativity->getName(),
                    "category" => $creativity->getCategory(),
                    "subcategory" => $creativity->getSubcategory(),
                    "url" => $this->generateUrl('admin_creativity_order_custom_create', array('id' => $creativity->getId())),
                    "image" => $this->getParameter('app.path.creativities') . '/' . $image->getFile(),
                );
            }
        }

        return new JsonResponse($response);
    }

//	/**
//	 * @param Request $request
//	 *
//	 * @Route("/image_upload" ,name="client_upload_image")
//	 * @return JsonResponse
//	 */
//	public function documentUploadAction(Request $request)
//	{
//		$filename = null;
//		$files_upload = $request->files->getIterator();
//		foreach($files_upload as $k => $i) {
//			$file = $request->files->get($k);
//			if($file) {
//				$filename= uniqid(rand(), true).'.'.$file->getClientOriginalExtension();
//				$file->move(
//					$this->get('webapp.media_resolver')->getRelRootPath('client.image'),
//					$filename
//				);
//			}
//		}
//
//		return new JsonResponse($filename);
//	}
}
