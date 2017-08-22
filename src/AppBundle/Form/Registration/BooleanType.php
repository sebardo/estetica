<?php

namespace AppBundle\Form\Registration;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\OptionsResolver\OptionsResolver;

class BooleanType extends AbstractType
{
	public function configureOptions(OptionsResolver $resolver)
	{
		$resolver->setDefaults(array(
			'choices' => array(
				0 => 'app.boolean.verdadero',
				1 => 'app.boolean.falso',
			)
		));
	}

	public function getParent()
	{
		return ChoiceType::class;
	}
}