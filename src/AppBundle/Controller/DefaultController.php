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

        return $this->render('@App/index.html.twig', array(
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
        $form = $this->createForm(new RegistrationType($this->container), $entity, array('edit_form' => false));
        $form->add('submit', 'Symfony\Component\Form\Extension\Core\Type\SubmitType', array('label' => $this->get('translator')->trans('app.create_btn'),'attr'=>array('class'=>'btn btn-success')));
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $em = $this->getDoctrine()->getManager();
            $em->persist($entity);
            $em->flush();

            $this->get('session')->getFlashBag()->add('success', $this->get('translator')->trans('registration.create_succesfull'));

            return $this->redirectToRoute('homepage');
        }

        return $this->render('AppBundle:Registration:front_new.html.twig', array(
            'entity' => $entity,
            'form' => $form->createView(),
            'breadcrumbs' => $this->getBreadCrumbs(true, array("name" => "backend.create")),
            'active_side_bar' => $this->getActiveSidebar()
        ));
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
        $provinceCollection = $this->container->get('webapp.manager.province_manager')->getBy(array("country" => $countryId));

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
        $cityCollection = $this->container->get('webapp.manager.city_manager')->getBy(array("province" => $provinceId));
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
