<?php

namespace AppBundle\Validator\Constraints;


use Symfony\Component\Validator\Constraint;

/**
 * @Annotation
 * Class ContainsValues
 * @package AppBundle\Validator\Constraints
 */
class ContainsValue extends Constraint
{
	public $message = 'min_one_value';

//	public function __construct($options)
//	{
//	    parent::__construct($options);
//	}

	public function validatedBy()
	{
		return ContainsValueValidator::class;
	}
}