<?php

namespace AppBundle\Controller;

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
     * @Route("/registration", name="front_registration_create")
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
