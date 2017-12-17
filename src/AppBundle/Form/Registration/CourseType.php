<?php


namespace AppBundle\Form\Registration;


use AppBundle\Entity\Registration;
use AppBundle\Entity\RegistrationHasCourse;
use AppBundle\Model\CourseModel;
use AppBundle\Model\RegistrationHasCourseModel;
use AppBundle\Services\Slugify;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class CourseType extends AbstractType
{
	private $entityModel;
	private $registrationHasCourseModel;
	private $data;

	public function __construct(CourseModel $entityModel, RegistrationHasCourseModel $registrationHasCourseModel, $data)
	{
		$this->entityModel = $entityModel;
		$this->registrationHasCourseModel = $registrationHasCourseModel;
		$this->data = ($data instanceof Registration) ? $data : null;
	}

	/**
	 * {@inheritdoc}
	 */
	public function buildForm(FormBuilderInterface $builder, array $options)
	{
                $returnValues = array();
                foreach ($this->data->getRegistrationsHaveCourses() as $cou) {
                    $returnValues[$cou->getCourse()->getId()] = $cou->getValue();
                }
                
		$courseCollection = $this->getChoicesByEntity();
		foreach ($courseCollection as $key => $course) {
			$builder
				->add('course_' . Slugify::slug($key), 'checkbox', array(
					'label' => $course,
					'required' => false,
					'attr' => array('class' => ''),
					'mapped' => false,
					'data' => (array_key_exists($key, $returnValues)) ? true : $this->getCheckedByRegistrationAndCourseId($this->data, $key)
				))
				->add('course_' . Slugify::slug($key) . '_detail', 'textarea', array(
					'label' => false,
					'required' => false,
					'attr' => array('class' => 'field-detail'),
					'mapped' => false,
					'data' => (array_key_exists($key, $returnValues)) ? $returnValues[$key] : $this->getValueByRegistrationAndCourseId($this->data, $key)
				));
		}
	}

	private function getChoicesByEntity()
	{
		$response = array();
		$entityCollection = $this->entityModel->getBy(array());
		foreach ($entityCollection as $entity) {
			$response[$entity->getId()] = $entity->getName();
		}

		return $response;
	}

	private function getCheckedByRegistrationAndCourseId($registration, $courseId)
	{
		if($registration instanceof Registration) {
			/** @var RegistrationHasCourse $registrationHasCourse */
			$registrationHasCourse = $this->registrationHasCourseModel->getOneBy(array('registration' => $registration, 'course' => $courseId));
			if(!empty($registrationHasCourse)){
				return true;
			}
		}

		return false;
	}

	private function getValueByRegistrationAndCourseId($registration, $courseId)
	{
		if($registration instanceof Registration) {
			/** @var RegistrationHasCourse $registrationHasCourse */
			$registrationHasCourse = $this->registrationHasCourseModel->getOneBy(array('registration' => $registration, 'course' => $courseId));
			if(!empty($registrationHasCourse)){
				return $registrationHasCourse->getValue();
			}
		}

		return null;
	}

	public function configureOptions(OptionsResolver $resolver)
	{
		$resolver->setDefaults(array());
	}
}