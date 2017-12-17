<?php


namespace AppBundle\Form\Registration;


use AppBundle\Entity\Registration;
use AppBundle\Entity\Registration\ParentSpeciality;
use AppBundle\Entity\RegistrationHasSpeciality;
use AppBundle\Model\RegistrationHasSpecialityModel;
use AppBundle\Model\SpecialityModel;
use AppBundle\Services\Slugify;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class SpecialityType extends AbstractType
{
	private $parentSpeciality;
	private $entityModel;
	private $registrationHasSpecialityModel;
	private $data;

	public function __construct(SpecialityModel $entityModel, RegistrationHasSpecialityModel $registrationHasSpecialityModel, ParentSpeciality $parentSpeciality, $data)
	{
		$this->entityModel = $entityModel;
		$this->registrationHasSpecialityModel = $registrationHasSpecialityModel;
		$this->parentSpeciality = $parentSpeciality;
		$this->data = ($data instanceof Registration) ? $data : null;
	}

	/**
	 * {@inheritdoc}
	 */
	public function buildForm(FormBuilderInterface $builder, array $options)
	{
                                
                $returnValues = array();
                foreach ($this->data->getRegistrationsHaveSpecialities() as $spe) {
                    if($spe instanceof RegistrationHasSpeciality) 
                        $returnValues[$spe->getSpeciality()->getId()] = $spe->getValue();
                }

                $specialityCollection = $this->getChoicesByEntity();
		foreach ($specialityCollection as $key => $speciality) {
			$builder
				->add('speciality_' . Slugify::slug($key), 'checkbox', array(
					'label' => $speciality,
					'required' => false,
					'attr' => array('class' => ''),
					'mapped' => false,
					'data' => (array_key_exists($key, $returnValues)) ? true : $this->getCheckedByRegistrationAndSpecialityId($this->data, $key)
				))
				->add('speciality_' . Slugify::slug($key) . '_detail', 'textarea', array(
					'label' => false,
					'required' => false,
					'attr' => array('class' => 'field-detail'),
					'mapped' => false,
					'data' => (array_key_exists($key, $returnValues)) ? $returnValues[$key] : $this->getValueByRegistrationAndSpecialityId($this->data, $key)
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

	private function getCheckedByRegistrationAndSpecialityId($registration, $specialityId)
	{
		if($registration instanceof Registration) {
			/** @var RegistrationHasSpeciality $registrationHasSpeciality */
			$registrationHasSpeciality = $this->registrationHasSpecialityModel->getOneBy(array('registration' => $registration, 'speciality' => $specialityId));
			if(!empty($registrationHasSpeciality)){
				return true;
			}
		}

		return false;
	}

	private function getValueByRegistrationAndSpecialityId($registration, $specialityId)
	{
		if($registration instanceof Registration) {
			/** @var RegistrationHasSpeciality $registrationHasSpeciality */
			$registrationHasSpeciality = $this->registrationHasSpecialityModel->getOneBy(array('registration' => $registration, 'speciality' => $specialityId));
			if(!empty($registrationHasSpeciality)){
				return $registrationHasSpeciality->getValue();
			}
		}

		return null;
	}

	public function configureOptions(OptionsResolver $resolver)
	{
		$resolver->setDefaults(array());
	}

}