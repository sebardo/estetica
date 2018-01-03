<?php

namespace EditorBundle\Form;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class TemplatePrintType extends AbstractType
{
    /**
     * {@inheritdoc}
     */
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
                ->add('print', 'checkbox', array(
                        'label' => 'creativity_order.form.print',
                        'required' => false,
                        'label_attr' => array('class' => ''),
                        'attr' => array('class' => '')
                ))
        ;
    }
    
    /**
     * {@inheritdoc}
     */
    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults(array(
            'data_class' => 'EditorBundle\Entity\Template'
        ));
    }

}
