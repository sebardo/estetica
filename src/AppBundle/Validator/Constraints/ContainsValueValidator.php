<?php


namespace AppBundle\Validator\Constraints;


use Symfony\Component\Validator\Constraint;
use Symfony\Component\Validator\ConstraintValidator;

class ContainsValueValidator extends ConstraintValidator
{
	/**
	 * Checks if the passed value is valid.
	 *
	 * @param mixed      $value The value that should be validated
	 * @param Constraint $constraint The constraint for the validation
	 */
	public function validate($value, Constraint $constraint)
	{
		if(count($value) == 0){
			$this->context->addViolation($constraint->message);
		}
	}
}