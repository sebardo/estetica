<?php

namespace AppBundle\Form\Order;

use AppBundle\Entity\Creativity;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\CallbackTransformer;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class CreativityOrderFieldType extends AbstractType
{
	private $support;

	/**
	 * {@inheritdoc}
	 */
	public function buildForm(FormBuilderInterface $builder, array $options)
	{
		$this->support = $options['support'];
		$entity = $builder->getData();
		if(!empty($this->support)) {
			$fieldCollection = Creativity::getSupportFields($this->support);
			foreach ($fieldCollection as $field) {
				$fieldFormatted = str_replace('-', '_', $field);
				$formFieldType = (strpos($fieldFormatted, 'content') === false) ? 'text' : 'textarea';
				$builder
					->add($fieldFormatted, $formFieldType, array(
						'mapped' => false,
						'label' => 'creativity_order.form.'. $this->support . '.' . $fieldFormatted,
						'required' => false,
						'label_attr' => array('class' => ''),
						'attr' => array('class' => 'custom-field-value')
					));
				$builder->get($fieldFormatted)->addModelTransformer(new CallbackTransformer(
					function ($value) use ($entity, $field) {
						$valueFieldCollection = $entity->getFieldsValue();
						return (array_key_exists($field, $valueFieldCollection)) ? $valueFieldCollection[$field] : '';
					},
					function ($valueField) use ($entity, $field) {
						$valueFieldCollection = $entity->getFieldsValue();
						$valueFieldCollection[$field] = $valueField;
						$entity->setFieldsValue($valueFieldCollection);
						$newValueFieldCollection = $entity->getFieldsValue();
						return $newValueFieldCollection;
					}
				));
			}
		}
	}

	/**
	 * {@inheritdoc}
	 */
	public function configureOptions(OptionsResolver $resolver)
	{
		$resolver->setDefaults(array(
			'data_class' => 'AppBundle\Entity\CreativityOrder',
			'support' => null
		));
	}

	/**
	 * {@inheritdoc}
	 */
	public function getBlockPrefix()
	{
		return 'appbundle_creativityorder_field';
	}


}
