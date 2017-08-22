<?php


namespace AppBundle\Form\Registration;


use AppBundle\Model\CourseModel;
use AppBundle\Services\Slugify;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class CourseType extends AbstractType
{
	private $entityModel;

	public function __construct(CourseModel $entityModel)
	{
		$this->entityModel = $entityModel;
	}

	/**
	 * {@inheritdoc}
	 */
	public function buildForm(FormBuilderInterface $builder, array $options)
	{
		$courseCollection = $this->getChoicesByEntity();
		foreach ($courseCollection as $course) {
			$builder
				->add('course_' . Slugify::slug($course), 'checkbox', array(
					'label' => $course,
					'required' => false,
					'attr' => array('class' => ''),
					'mapped' => false,
				))
				->add('course_' . Slugify::slug($course) . '_detail', 'textarea', array(
					'label' => false,
					'required' => false,
					'attr' => array('class' => ''),
					'mapped' => false,
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

	public function configureOptions(OptionsResolver $resolver)
	{
		$resolver->setDefaults(array());
	}
}