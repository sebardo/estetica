<?php

namespace AppBundle\Form;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use AppBundle\Form\ImageType;

/**
 * Class HomeImagesType
 */
class HomeImagesType extends AbstractType
{
    /**
     * {@inheritDoc}
     */
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $conf = array();
        if(isset($options['uploadDir'])){
            $conf['uploadDir'] = $options['uploadDir'];
        }
        if(isset($options['required'])){
            $conf['required'] = true;
        }
        $builder
            ->add(
                $builder->create('image', ImageType::class, $conf)
            )
            ->add(
                $builder->create('image2', ImageType::class, $conf)
            )
            ->add(
                $builder->create('image3', ImageType::class, $conf)
            )
            ;
    }

    /**
     * {@inheritDoc}
     */
    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults(array(
            'data_class' =>  'AppBundle\Entity\HomeImages',
            'uploadDir' => null,
            'required' => null
        ));
    }
}
