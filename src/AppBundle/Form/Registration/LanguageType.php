<?php


namespace AppBundle\Form\Registration;


use AppBundle\Entity\Registration;
use AppBundle\Entity\Registration\Language;
use AppBundle\Entity\RegistrationHasLanguage;
use AppBundle\Model\LanguageModel;
use AppBundle\Model\RegistrationHasLanguageModel;
use AppBundle\Services\Slugify;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class LanguageType extends AbstractType
{
	const LOW_LEVEL = 'low';
	const MIDDLE_LEVEL = 'middle';
	const HIGH_LEVEL = 'high';
	const NATIVE_LEVEL = 'native';

	private $entityModel;
	private $registrationHasLanguageModel;
	private $data;

	public function __construct(LanguageModel $entityModel, RegistrationHasLanguageModel $registrationHasLanguageModel, $data)
	{
		$this->entityModel = $entityModel;
		$this->registrationHasLanguageModel = $registrationHasLanguageModel;
		$this->data = ($data instanceof Registration) ? $data : null;
	}

	/**
	 * {@inheritdoc}
	 */
	public function buildForm(FormBuilderInterface $builder, array $options)
	{
		$languageCollection = $this->getChoicesByEntity();

		foreach ($languageCollection as $key => $language) {
			$builder
				->add('language_' . Slugify::slug($key), 'checkbox', array(
					'label' => $language,
					'required' => false,
					'attr' => array('class' => ''),
					'mapped' => false,
					'data' => $this->getCheckedByRegistrationAndLanguageId($this->data, $key)
				))
				->add('language_' . Slugify::slug($key) . '_detail', 'choice', array(
					'choices' => $this->getLevelChoicesByEntity(),
					'label' => false,
					'expanded' => false,
					'multiple' => false,
					'attr' => array('class' => ''),
					'mapped' => false,
					'data' => $this->getValueByRegistrationAndLanguageId($this->data, $key)
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

	private function getLevelChoicesByEntity()
	{
		$response = array();
		$response[self::LOW_LEVEL] = 'app.language_level.' . self::LOW_LEVEL;
		$response[self::MIDDLE_LEVEL] = 'app.language_level.' . self::MIDDLE_LEVEL;
		$response[self::HIGH_LEVEL] = 'app.language_level.' . self::HIGH_LEVEL;
		$response[self::NATIVE_LEVEL] = 'app.language_level.' . self::NATIVE_LEVEL;

		return $response;
	}

	private function getCheckedByRegistrationAndLanguageId($registration, $languageId)
	{
		if($registration instanceof Registration) {
			/** @var RegistrationHasLanguage $registrationHasLanguage */
			$registrationHasLanguage = $this->registrationHasLanguageModel->getOneBy(array('registration' => $registration, 'language' => $languageId));
			if(!empty($registrationHasLanguage)){
				return true;
			}
		}

		return false;
	}

	private function getValueByRegistrationAndLanguageId($registration, $languageId)
	{
		if($registration instanceof Registration) {
			/** @var RegistrationHasLanguage $registrationHasLanguage */
			$registrationHasLanguage = $this->registrationHasLanguageModel->getOneBy(array('registration' => $registration, 'language' => $languageId));
			if(!empty($registrationHasLanguage)){
				return $registrationHasLanguage->getValue();
			}
		}

		return null;
	}

	public function configureOptions(OptionsResolver $resolver)
	{
		$resolver->setDefaults(array());
	}
}