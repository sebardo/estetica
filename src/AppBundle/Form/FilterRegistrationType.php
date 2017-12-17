<?php


namespace AppBundle\Form;


use AppBundle\Entity\AcademicStudy;
use AppBundle\Entity\Registration\ContractType;
use AppBundle\Entity\Registration\Course;
use AppBundle\Entity\Registration\Experience;
use AppBundle\Entity\Registration\Language;
use AppBundle\Entity\Registration\LevelResponsibility;
use AppBundle\Entity\Registration\Speciality;
use AppBundle\Entity\Registration\Study;
use AppBundle\Entity\Registration\TimeAvailability;
use AppBundle\Form\EventListener\AddCityFieldSubscriber;
use AppBundle\Form\EventListener\AddCountryFieldSubscriber;
use AppBundle\Form\EventListener\AddProvinceFieldSubscriber;
use AppBundle\Form\EventListener\AddSpecialityFieldSubscriber;
use AppBundle\Form\Registration\ParentSpecialityType;
use AppBundle\Form\Types\BooleanType;
use AppBundle\Model\RegistrationModel;
use Symfony\Bundle\FrameworkBundle\Routing\Router;
use Symfony\Component\DependencyInjection\ContainerInterface;
use Symfony\Component\Form\FormFactory;
use Symfony\Component\HttpFoundation\Request;

class FilterRegistrationType
{
	/** @var FormFactory $formFactory */
	private $formFactory;

	/** @var Router $router */
	private $router;

	/** @var ContainerInterface $container */
	private $container;

	public function __construct(FormFactory $formFactory, Router $router, ContainerInterface $containerInterface)
	{
		$this->formFactory = $formFactory;
		$this->router = $router;
		$this->container = $containerInterface;
	}

	public function buildForm($dataForm)
	{
		$builder = $this->formFactory->createBuilder()
			->add('name', 'Symfony\Component\Form\Extension\Core\Type\TextType', array(
				'label' => 'registration.filter_form.name.name',
				'required' => false,
				'attr' => array(
					'class' => 'no-required',
					'placeholder' => 'registration.filter_form.name.default'
				),
				'data' => empty($dataForm['name']) ? '' : $dataForm['name']
			))
			->add('surname', 'Symfony\Component\Form\Extension\Core\Type\TextType', array(
				'label' => 'registration.filter_form.surname.name',
				'required' => false,
				'attr' => array(
					'class' => 'no-required',
					'placeholder' => 'registration.filter_form.surname.default'
				),
				'data' => empty($dataForm['surname']) ? '' : $dataForm['surname']
			))
			->add('email', 'Symfony\Component\Form\Extension\Core\Type\TextType', array(
				'label' => 'registration.filter_form.email.name',
				'required' => false,
				'attr' => array(
					'class' => 'no-required',
					'placeholder' => 'registration.filter_form.email.default'
				),
				'data' => empty($dataForm['email']) ? '' : $dataForm['email']
			))
			->add('parentSpeciality', new ParentSpecialityType($this->container->get('webapp.manager.parent_speciality_manager')), array(
				'label' => 'registration.filter_form.parent_speciality.name',
				'placeholder' => 'registration.filter_form.parent_speciality.default',
				'required' => false,
				'expanded' => false,
				'multiple' => false,
				'attr' => array(
					'class' => 'no-required'
				),
				'data' => empty($dataForm['parentSpeciality']) ? '' : $dataForm['parentSpeciality']
			))
			->add('speciality', 'Symfony\Component\Form\Extension\Core\Type\ChoiceType', array(
				'choices' => empty($dataForm['parentSpeciality']) ? null : $this->getSpecialityCollection($dataForm['parentSpeciality']),
				'label' => 'registration.filter_form.speciality.name',
				'placeholder' => 'registration.filter_form.speciality.default',
				'required' => false,
				'expanded' => false,
				'multiple' => true,
				'attr' => array(
					'class' => 'no-required selectpicker',
					'autocomplete' => 'off',
					'title' => 'registration.filter_form.speciality.default'
				),
				'data' => empty($dataForm['speciality']) ? null : $dataForm['speciality']
			))
			->add('experience', 'Symfony\Component\Form\Extension\Core\Type\ChoiceType', array(
				'choices' => $this->getExperienceCollection(),
				'label' => 'registration.filter_form.experience.name',
				'required' => true,
				'label_attr' => array('class' => ''),
				'attr' => array('class' => ''),
				'data' => empty($dataForm['experience']) ? -1 : $dataForm['experience']
			))
            ->add('experiencePlaces', 'Symfony\Component\Form\Extension\Core\Type\TextType', array(
				'label' => 'registration.filter_form.experiencePlaces.name',
				'required' => false,
				'attr' => array(
					'class' => 'no-required',
					'placeholder' => 'registration.filter_form.experiencePlaces.default'
				),
				'data' => empty($dataForm['experiencePlaces']) ? '' : $dataForm['experiencePlaces']
			))
			->add('vehicle', new BooleanType(), array(
				'label' => 'registration.filter_form.vehicle.name',
				'required' => false,
				'placeholder' => 'registration.filter_form.vehicle.default',
				'label_attr' => array('class' => ''),
				'attr' => array(
					'class' => ''
				)
			))
			->add('travelAvailability', new BooleanType(), array(
				'label' => 'registration.filter_form.travel_availability.name',
				'required' => false,
				'placeholder' => 'registration.filter_form.travel_availability.default',
				'label_attr' => array('class' => ''),
				'attr' => array(
					'class' => ''
				)
			))
			->add('timeAvailability', 'Symfony\Component\Form\Extension\Core\Type\ChoiceType', array(
				'choices' => $this->getTimeAvailabilityCollection(),
				'label' => 'registration.filter_form.time_availability.name',
				'required' => true,
				'label_attr' => array('class' => ''),
				'attr' => array('class' => ''),
				'data' => empty($dataForm['timeAvailability']) ? -1 : $dataForm['timeAvailability']
			))
			->add('language', 'Symfony\Component\Form\Extension\Core\Type\ChoiceType', array(
				'choices' => $this->getLanguageCollection(),
				'label' => 'registration.filter_form.language.name',
				'required' => true,
				'label_attr' => array('class' => ''),
				'attr' => array('class' => 'selectpicker', 'autocomplete' => 'off', 'title' => 'registration.filter_form.language.name'),
				'expanded' => false,
				'multiple' => true,
				'data' => empty($dataForm['language']) ? null : $dataForm['language']
			))
			->add('certificateDisability', new BooleanType(), array(
				'label' => 'registration.filter_form.certificate_disability.name',
				'required' => false,
				'placeholder' => 'registration.filter_form.certificate_disability.default',
				'label_attr' => array('class' => ''),
				'attr' => array(
					'class' => ''
				)
			))
			->add('salesTraining', new BooleanType(), array(
				'label' => 'registration.filter_form.sales_training.name',
				'required' => false,
				'placeholder' => 'registration.filter_form.sales_training.default',
				'label_attr' => array('class' => ''),
				'attr' => array(
					'class' => ''
				)
			))
			->add('contractType', 'Symfony\Component\Form\Extension\Core\Type\ChoiceType', array(
				'choices' => $this->getContractTypeCollection(),
				'label' => 'registration.filter_form.contract_type.name',
				'required' => true,
				'label_attr' => array('class' => ''),
				'attr' => array('class' => ''),
				'data' => empty($dataForm['contractType']) ? -1 : $dataForm['contractType']
			))
			->add('levelResponsibility', 'Symfony\Component\Form\Extension\Core\Type\ChoiceType', array(
				'choices' => $this->getLevelResponsibilityCollection(),
				'label' => 'registration.filter_form.level_responsibility.name',
				'required' => true,
				'label_attr' => array('class' => ''),
				'attr' => array('class' => ''),
				'data' => empty($dataForm['levelResponsibility']) ? -1 : $dataForm['levelResponsibility']
			))
			->add('study', 'Symfony\Component\Form\Extension\Core\Type\ChoiceType', array(
				'choices' => $this->getStudyCollection(),
				'label' => 'registration.filter_form.study.name',
				'required' => true,
				'label_attr' => array('class' => ''),
				'attr' => array('class' => ''),
				'data' => empty($dataForm['study']) ? -1 : $dataForm['study']
			))
			->add('course', 'Symfony\Component\Form\Extension\Core\Type\ChoiceType', array(
				'choices' => $this->getCourseCollection(),
				'label' => 'registration.filter_form.course.name',
				'required' => true,
				'label_attr' => array('class' => ''),
				'attr' => array('class' => 'selectpicker', 'autocomplete' => 'off', 'title' => 'registration.filter_form.course.name'),
				'expanded' => false,
				'multiple' => true,
				'data' => empty($dataForm['course']) ? null : $dataForm['course']
			))
			->add('academicStudy', 'Symfony\Component\Form\Extension\Core\Type\ChoiceType', array(
				'choices' => $this->getAcademicStudyCollection(),
				'label' => 'registration.filter_form.academic_study.name',
				'required' => true,
				'label_attr' => array('class' => ''),
				'attr' => array('class' => ''),
				'data' => empty($dataForm['academicStudy']) ? -1 : $dataForm['academicStudy']
			))
			->add('postal_code', 'Symfony\Component\Form\Extension\Core\Type\TextType', array(
				'label' => 'registration.filter_form.postal_code.name',
				'required' => false,
				'attr' => array(
					'class' => 'no-required',
					'placeholder' => 'registration.filter_form.postal_code.default'
				),
				'data' => empty($dataForm['postal_code']) ? '' : $dataForm['postal_code']
			))
			->add('submit', 'Symfony\Component\Form\Extension\Core\Type\SubmitType', array('label' => $this->container->get('translator')->trans('app.search_btn'), 'attr' => array('class' => 'btn btn-success')));

		$options = array(
			'required_form' => false
		);
		$countrySubscriber = new AddCountryFieldSubscriber($this->formFactory, $options);
		$builder->addEventSubscriber($countrySubscriber);
		$provinceSubscriber = new AddProvinceFieldSubscriber($this->formFactory, $options);
		$builder->addEventSubscriber($provinceSubscriber);
		$citySubscriber = new AddCityFieldSubscriber($this->formFactory, $options);
		$builder->addEventSubscriber($citySubscriber);

		return $builder
			->setAction($this->router->generate('admin_registration_list'))
			->setMethod('GET')
			->getForm();

	}

	private function getSpecialityCollection($parentSpecialityId)
	{
		$response = array();
		$_entityCollection = $this->container->get('webapp.manager.speciality_manager')->getBy(array('parent' => $parentSpecialityId));

		foreach($_entityCollection as $_entity){
			if($_entity instanceof Speciality){
				$response[$_entity->getId()] = $_entity->getName();
			}
		}

		return $response;
	}

	private function getExperienceCollection()
	{
		$response = array(
			'-1' => 'registration.filter_form.experience.default'
		);

		$_entityCollection = $this->container->get('webapp.manager.experience_manager')->getBy(array());

		foreach($_entityCollection as $_entity){
			if($_entity instanceof Experience){
				$response[$_entity->getId()] = $_entity->getName();
			}
		}

		return $response;
	}

	private function getTimeAvailabilityCollection()
	{
		$response = array(
			'-1' => 'registration.filter_form.time_availability.default'
		);

		$_entityCollection = $this->container->get('webapp.manager.time_availability_manager')->getBy(array());

		foreach($_entityCollection as $_entity){
			if($_entity instanceof TimeAvailability){
				$response[$_entity->getId()] = $_entity->getName();
			}
		}

		return $response;
	}

	private function getContractTypeCollection()
	{
		$response = array(
			'-1' => 'registration.filter_form.contract_type.default'
		);

		$_entityCollection = $this->container->get('webapp.manager.contract_type_manager')->getBy(array());

		foreach($_entityCollection as $_entity){
			if($_entity instanceof ContractType){
				$response[$_entity->getId()] = $_entity->getName();
			}
		}

		return $response;
	}

	private function getLevelResponsibilityCollection()
	{
		$response = array(
			'-1' => 'registration.filter_form.level_responsibility.default'
		);

		$_entityCollection = $this->container->get('webapp.manager.level_responsibility_manager')->getBy(array());

		foreach($_entityCollection as $_entity){
			if($_entity instanceof LevelResponsibility){
				$response[$_entity->getId()] = $_entity->getName();
			}
		}

		return $response;
	}

	private function getStudyCollection()
	{
		$response = array(
			'-1' => 'registration.filter_form.study.default'
		);

		$_entityCollection = $this->container->get('webapp.manager.study_manager')->getBy(array());

		foreach($_entityCollection as $_entity){
			if($_entity instanceof Study){
				$response[$_entity->getId()] = $_entity->getName();
			}
		}

		return $response;
	}

	private function getAcademicStudyCollection()
	{
		$response = array(
			'-1' => 'registration.filter_form.academic_study.default'
		);

		$_entityCollection = $this->container->get('webapp.manager.academic_manager')->getBy(array());

		foreach($_entityCollection as $_entity){
			if($_entity instanceof AcademicStudy){
				$response[$_entity->getId()] = $_entity->getName();
			}
		}

		return $response;
	}

	private function getLanguageCollection()
	{
		$response = array();
		$_entityCollection = $this->container->get('webapp.manager.language_manager')->getBy(array());

		foreach($_entityCollection as $_entity){
			if($_entity instanceof Language){
				$response[$_entity->getId()] = $_entity->getName();
			}
		}

		return $response;
	}

	private function getCourseCollection()
	{
		$response = array();
		$_entityCollection = $this->container->get('webapp.manager.course_manager')->getBy(array());

		foreach($_entityCollection as $_entity){
			if($_entity instanceof Course){
				$response[$_entity->getId()] = $_entity->getName();
			}
		}

		return $response;
	}
}