<?php


namespace AppBundle\Form\Registration;


use AppBundle\Entity\Registration\Language;
use AppBundle\Model\LanguageModel;
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

	public function __construct(LanguageModel $entityModel)
	{
		$this->entityModel = $entityModel;
	}

	/**
	 * {@inheritdoc}
	 */
	public function buildForm(FormBuilderInterface $builder, array $options)
	{
		$languageCollection = $this->getChoicesByEntity();

		foreach ($languageCollection as $language) {
			$builder
				->add('language_' . Slugify::slug($language), 'checkbox', array(
					'label' => $language,
					'required' => false,
					'attr' => array('class' => ''),
					'mapped' => false,
				))
				->add('language_' . Slugify::slug($language) . '_detail', 'choice', array(
					'choices' => $this->getLevelChoicesByEntity(),
					'label' => false,
					'expanded' => false,
					'multiple' => false,
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

	private function getLevelChoicesByEntity()
	{
		$response = array();
		$response[self::LOW_LEVEL] = 'app.language_level.' . self::LOW_LEVEL;
		$response[self::MIDDLE_LEVEL] = 'app.language_level.' . self::MIDDLE_LEVEL;
		$response[self::HIGH_LEVEL] = 'app.language_level.' . self::HIGH_LEVEL;
		$response[self::NATIVE_LEVEL] = 'app.language_level.' . self::NATIVE_LEVEL;

		return $response;
	}

	public function configureOptions(OptionsResolver $resolver)
	{
		$resolver->setDefaults(array());
	}
}