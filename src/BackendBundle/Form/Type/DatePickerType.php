<?php


namespace BackendBundle\Form\Type;

use Symfony\Component\Form\AbstractType;

class DatePickerType extends AbstractType
{
    public function getParent()
    {
        return 'Symfony\Component\Form\Extension\Core\Type\TextType';
    }

    /**
     * Returns the name of this type.
     *
     * @return string The name of this type
     */
    public function getBlockPrefix()
    {
        return 'date_picker_type';
    }
}