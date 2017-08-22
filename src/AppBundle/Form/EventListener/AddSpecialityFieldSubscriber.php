<?php


namespace AppBundle\Form\EventListener;


use AppBundle\Entity\Registration\ParentSpeciality;
use AppBundle\Form\Registration\ParentSpecialityType;
use AppBundle\Form\Registration\SpecialityType;
use AppBundle\Services\Slugify;
use Symfony\Component\DependencyInjection\ContainerInterface;
use Symfony\Component\EventDispatcher\EventSubscriberInterface;
use Symfony\Component\Form\FormEvent;
use Symfony\Component\Form\FormEvents;
use Symfony\Component\Form\FormInterface;

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

	private function addSpecialityForm(FormInterface $form, $parentSpeciality, $required)
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
		if($required && !empty($parentSpeciality)){
			$form->add('speciality_' . Slugify::slug($parentSpeciality->getFormatName()), new SpecialityType($this->container->get('webapp.manager.speciality_manager'), $parentSpeciality), array(
					'label' => 'registration.form.speciality.name',
					'required' => $required,
					'label_attr' => array('class' => ''),
					'attr' => array('class' => 'speciality_children hide'),
					'mapped' => false
				)
			);
		}else {
			foreach ($this->parentSpecialityCollection as $_parentSpeciality) {
				$form->add('speciality_' . Slugify::slug($_parentSpeciality->getFormatName()), new SpecialityType($this->container->get('webapp.manager.speciality_manager'), $_parentSpeciality), array(
						'label' => 'registration.form.speciality.name',
						'required' => $required,
						'label_attr' => array('class' => ''),
						'attr' => array('class' => 'speciality_children hide'),
						'mapped' => false
					)
				);
			}
		}
	}

	public function preSetData(FormEvent $event)
	{
		$data = $event->getData();
		$form = $event->getForm();

		$parentSpeciality = $this->parentSpecialityDefault;

		$this->addSpecialityForm($form, $parentSpeciality, false);
	}

	public function preBind(FormEvent $event)
	{
		$data = $event->getData();
		$form = $event->getForm();

		if (null === $data) {
			return;
		}

		$parentSpeciality = array_key_exists('parentSpeciality', $data) ? $this->container->get('webapp.manager.parent_speciality_manager')->getOneBy(array('id' => $data['parentSpeciality'])) : $this->parentSpecialityDefault;
		$this->addSpecialityForm($form, $parentSpeciality, true);
	}
}