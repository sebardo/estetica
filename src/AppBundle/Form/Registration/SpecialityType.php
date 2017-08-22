<?php


namespace AppBundle\Form\Registration;


use AppBundle\Entity\Registration\ParentSpeciality;
use AppBundle\Entity\Registration\Speciality;
use AppBundle\Model\SpecialityModel;
use AppBundle\Services\Slugify;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class SpecialityType extends AbstractType
{
	private $parentSpeciality;
	private $entityModel;

	public function __construct(SpecialityModel $entityModel, ParentSpeciality $parentSpeciality)
	{
		$this->entityModel = $entityModel;
		$this->parentSpeciality = $parentSpeciality;
	}

	/**
	 * {@inheritdoc}
	 */
	public function buildForm(FormBuilderInterface $builder, array $options)
	{
		$specialityCollection = $this->getChoicesByEntity();
		foreach ($specialityCollection as $speciality) {
			$builder
				->add('speciality_' . Slugify::slug($speciality), 'checkbox', array(
					'label' => $speciality,
					'required' => false,
					'attr' => array('class' => ''),
					'mapped' => false,
				))
				->add('speciality_' . Slugify::slug($speciality) . '_detail', 'textarea', array(
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
		$entityCollection = $this->entityModel->getBy(array('parent' => $this->parentSpeciality));
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