<?php

namespace AppBundle\Form\Registration;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\OptionsResolver\OptionsResolver;

class GenderType extends AbstractType
{
	public function configureOptions(OptionsResolver $resolver)
	{
		$resolver->setDefaults(array(
			'choices' => array(
				'm' => 'app.gender.male',
				'f' => 'app.gender.female',
			)
		));
	}

	public function getParent()
	{
		return ChoiceType::class;
	}
}