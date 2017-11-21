<?php

namespace AppBundle\Form;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Form\Extension\Core\Type\UrlType;
use Symfony\Component\Form\Extension\Core\Type\HiddenType;
use AppBundle\Form\ImageType;

/**
 * Class SliderType
 */
class SliderType extends AbstractType
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
        
        $builder
            ->add('title')
            //->add('caption')
            ->add(
                $builder->create('image', ImageType::class, $conf)
            )
            ->add('removeImage', HiddenType::class, array('required' => false, 'attr' => array(
                'class' => 'remove-image'
                )))
            ->add('openInNewWindow', null, array('required' => false))
            ->add('url', UrlType::class, array('required' => false))
            ->add('active', null, array('required' => false))
            ;
    }

    /**
     * {@inheritDoc}
     */
    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults(array(
            'data_class' =>  'AppBundle\Entity\Slider',
            'uploadDir' => null
        ));
    }
}
