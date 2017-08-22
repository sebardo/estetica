<?php


namespace AppBundle\Form\Registration;


use AppBundle\Entity\Registration\ParentSpeciality;
use AppBundle\Model\ParentSpecialityModel;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\OptionsResolver\OptionsResolver;

class ParentSpecialityType extends AbstractType
{
	private $entityModel;

	public function __construct(ParentSpecialityModel $entityModel)
	{
		$this->entityModel = $entityModel;
	}
	public function configureOptions(OptionsResolver $resolver)
	{
		$resolver->setDefaults(array(
			'choices' => $this->getChoicesByEntity(),
			'expanded' => true,
			'multiple' => false
		));
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

	public function getParent()
	{
		return ChoiceType::class;
	}
}