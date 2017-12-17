<?php


namespace AppBundle\Form\EventListener;


use AppBundle\Entity\Registration;
use AppBundle\Entity\Registration\ParentSpeciality;
use AppBundle\Entity\Registration\Speciality;
use AppBundle\Entity\RegistrationHasSpeciality;
use AppBundle\Form\Registration\ParentSpecialityType;
use AppBundle\Form\Registration\SpecialityType;
use AppBundle\Model\SpecialityModel;
use AppBundle\Services\Slugify;
use Symfony\Component\DependencyInjection\ContainerInterface;
use Symfony\Component\EventDispatcher\EventSubscriberInterface;
use Symfony\Component\Form\FormError;
use Symfony\Component\Form\FormEvent;
use Symfony\Component\Form\FormEvents;
use Symfony\Component\Form\FormInterface;
use Symfony\Component\Validator\Constraints\Count;

class AddSpecialityFieldSubscriber implements EventSubscriberInterface
{
	const PARENT_SPECIALITY_DEFAULT = 'Estetica';
	private $parentSpecialityManager;
	private $parentSpecialityCollection;
	private $parentSpecialityDefault;
	private $container;
	private $options;

	public function __construct(ContainerInterface $container, $options = array())
	{
		$this->container = $container;
		$this->options = $options;
		$this->parentSpecialityManager = $this->container->get('webapp.manager.parent_speciality_manager');
		$this->parentSpecialityDefault = $this->parentSpecialityManager->getOneBy(array('formatName' => self::PARENT_SPECIALITY_DEFAULT));
		$this->parentSpecialityCollection = $this->parentSpecialityManager->getBy(array());
	}

	public static function getSubscribedEvents()
	{
		return array(
			FormEvents::PRE_SET_DATA => 'preSetData',
			FormEvents::PRE_SUBMIT     => 'preBind'
		);
	}

	private function addSpecialityForm(FormInterface $form, $parentSpeciality, $data)
	{
		$form->add('parentSpeciality', new ParentSpecialityType($this->container->get('webapp.manager.parent_speciality_manager')), array(
				'label' => 'registration.form.parent_speciality.name',
				'required' => true,
				'label_attr' => array('class' => ''),
				'attr' => array('class' => ''),
				'mapped' => false,
				'data' => ($parentSpeciality) ? $parentSpeciality->getId() : null
			)
		);
		foreach ($this->parentSpecialityCollection as $_parentSpeciality) {
			$hide = ($parentSpeciality == $_parentSpeciality) ? '' : 'hide';
			$form->add('speciality_' . Slugify::slug($_parentSpeciality->getFormatName()), new SpecialityType($this->container->get('webapp.manager.speciality_manager'), $this->container->get('webapp.manager.registration_has_speciality_manager'), $_parentSpeciality, $data), array(
					'label' => 'registration.form.speciality.name',
					'required' => true,
					'label_attr' => array('class' => ''),
					'attr' => array('class' => 'speciality_children ' . $hide),
					'mapped' => false
				)
			);
		}
	}

	public function preSetData(FormEvent $event)
	{
		$data = $event->getData();
		$form = $event->getForm();

		$parentSpeciality = $this->parentSpecialityDefault;
		if(!empty($data)){
			if($data instanceof Registration){
				$registrationHasSpecialityCollection = $data->getRegistrationsHaveSpecialities();
				foreach ($registrationHasSpecialityCollection as $registrationHasSpeciality) {
					if($registrationHasSpeciality instanceof RegistrationHasSpeciality) {
						$parentSpeciality = $registrationHasSpeciality->getSpeciality()->getParent();
					}
				}
			}
		}

		$this->addSpecialityForm($form, $parentSpeciality, $data);
	}

	public function preBind(FormEvent $event)
	{
		$data = $event->getData();
		$form = $event->getForm();

		if (null === $data) {
			return;
		}

		//Speciality
		$count = 0;
		$parentSpecialityId = $data['parentSpeciality'];
		/** @var ParentSpeciality $parentSpeciality */
		$parentSpeciality = $this->container->get('webapp.manager.parent_speciality_manager')->getOneBy(array('id' => $parentSpecialityId));
		if(!empty($parentSpeciality)){
			//Delete all registrationHasSpeciality
			$this->deleteRegistrationHasSpecialityCollectionByRegistrationEntity($form->getData());
			$extra = 'detail';
			$specialityName = 'speciality_' . Slugify::slug($parentSpeciality->getName());
			$specialityCollection = (array_key_exists($specialityName, $data)) ? $data[$specialityName] : null;
			foreach ($specialityCollection as $key => $value) {
				if(strpos($key, $extra) === false){
					$id = explode('_', $key)[1];
					$detailKey = $key . '_' . $extra;
					if(array_key_exists($detailKey, $data[$specialityName])){
						/** @var SpecialityModel $specialityManager */
						$specialityManager = $this->container->get('webapp.manager.speciality_manager');
						/** @var Speciality $speciality */
						$speciality = $specialityManager->getOneBy(array('parent' => $parentSpeciality, 'id' => $id));
						if(!empty($speciality)){
							$extraData = $data[$specialityName][$detailKey];
							if(!empty($extraData)){
								$entity = new RegistrationHasSpeciality();
								$entity->setRegistration($form->getData());
								$entity->setSpeciality($speciality);
								$entity->setValue($extraData);
								$specialityManager->save($entity, false);
							} else {
								$formError = new FormError($this->container->get('translator')->trans('registration.form.errors.speciality.detail', array('%field%' => $speciality->getName())), null, array(), null, 'speciality');
								$form->addError($formError);
							}
							$count++;
						}
					}
				}
			}
		}

//		if($count == 0) {
//			$formError = new FormError($this->container->get('translator')->trans('registration.form.errors.speciality.empty'), null, array(), null, 'parentSpeciality');
//			$form->addError($formError);
//			$form->get('parentSpeciality')->addError($formError);
//		}
	}

	private function deleteRegistrationHasSpecialityCollectionByRegistrationEntity(Registration $entity)
	{
		$registrationHasSpecialityModel = $this->container->get('webapp.manager.registration_has_speciality_manager');
		$registrationHasSpecialityCollection = $registrationHasSpecialityModel->getBy(array('registration' => $entity));
		foreach ($registrationHasSpecialityCollection as $registrationHasSpeciality) {
			$registrationHasSpecialityModel->remove($registrationHasSpeciality);
		}
	}
}