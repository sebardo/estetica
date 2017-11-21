<?php

namespace AppBundle\Form;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Form\Extension\Core\Type\HiddenType;

/**
 * Class ImageType
 */
class ImageType extends AbstractType
{
 
    /**
     * {@inheritDoc}
     */
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        if(isset($options['required'])){
            $confFile = array(
                'label' => 'image.singular',
                'required' => true
            );
        }else{
            $confFile = array(
                'label' => 'image.singular'
            );
        }
        
        if(isset($options['uploadDir'])){
            $conf['data'] = $options['uploadDir'];
        }
        $builder
            ->add('file', null, $confFile)
            ->add('uploadDir', HiddenType::class, $conf)
            ;
        
    }

    /**
     * {@inheritDoc}
     */
    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults(array(
            'data_class' => 'AppBundle\Entity\Image',
            'uploadDir' => null
        ));
    }

}
