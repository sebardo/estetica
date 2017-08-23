<?php


namespace AppBundle\Form\EventListener;

use AppBundle\Entity\Registration;
use AppBundle\Entity\Registration\Language;
use AppBundle\Entity\RegistrationHasLanguage;
use AppBundle\Form\Registration\LanguageType;
use AppBundle\Model\LanguageModel;
use Symfony\Component\DependencyInjection\ContainerInterface;
use Symfony\Component\EventDispatcher\EventSubscriberInterface;
use Symfony\Component\Form\FormError;
use Symfony\Component\Form\FormEvent;
use Symfony\Component\Form\FormEvents;
use Symfony\Component\Form\FormInterface;

class AddLanguageFieldSubscriber implements EventSubscriberInterface
{
	public function __construct(ContainerInterface $container, $options = array())
	{
		$this->container = $container;
		$this->options = $options;
	}

	public static function getSubscribedEvents()
	{
		return array(
			FormEvents::PRE_SET_DATA => 'preSetData',
			FormEvents::PRE_SUBMIT     => 'preBind'
		);
	}

	private function addCourseForm(FormInterface $form, $data)
	{
		$form
			->add('language', new LanguageType($this->container->get('webapp.manager.language_manager'), $this->container->get('webapp.manager.registration_has_language_manager'), $data), array(
				'label' => 'registration.form.language.name',
				'required' => true,
				'label_attr' => array('class' => ''),
				'attr' => array('class' => ''),
				'mapped' => false
			))
		;
	}

	public function preSetData(FormEvent $event)
	{
		$data = $event->getData();
		$form = $event->getForm();

		$this->addCourseForm($form, $data);
	}

	public function preBind(FormEvent $event)
	{
		$data = $event->getData();
		$form = $event->getForm();

		if (null === $data) {
			return;
		}

		//Language
		$count = 0;
		/** @var LanguageModel $languageManager */
		$languageManager = $this->container->get('webapp.manager.language_manager');
		//Delete all registrationHasLanguage
		$this->deleteRegistrationHasLanguageCollectionByRegistrationEntity($form->getData());
		$extra = 'detail';
		$languageName = 'language';
		$languageCollection = (array_key_exists($languageName, $data)) ? $data[$languageName] : null;
		foreach ($languageCollection as $key => $value) {
			if(strpos($key, $extra) === false){
				$id = explode('_', $key)[1];
				$detailKey = $key . '_' . $extra;
				if(array_key_exists($detailKey, $data[$languageName])){
					/** @var Language $language */
					$language = $languageManager->getOneBy(array('id' => $id));
					if(!empty($language)){
						$extraData = $data[$languageName][$detailKey];
						$entity = new RegistrationHasLanguage();
						$entity->setRegistration($form->getData());
						$entity->setLanguage($language);
						$entity->setValue($extraData);
						$languageManager->save($entity, false);
					}
				}
			}
		}

		if($count == 0) {
			$formError = new FormError($this->container->get('translator')->trans('registration.form.errors.language.empty'), null, array(), null, 'language');
			$form->addError($formError);
		}
	}

	private function deleteRegistrationHasLanguageCollectionByRegistrationEntity(Registration $entity)
	{
		$registrationHasLanguageModel = $this->container->get('webapp.manager.registration_has_language_manager');
		$registrationHasLanguageCollection = $registrationHasLanguageModel->getBy(array('registration' => $entity));
		foreach ($registrationHasLanguageCollection as $registrationHasLanguage) {
			$registrationHasLanguageModel->remove($registrationHasLanguage);
		}
	}
}