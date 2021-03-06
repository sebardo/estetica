<?php

namespace AppBundle\Form;

use AppBundle\Form\EventListener\ValidateUrlVideoOrImageSubscriber;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Vich\UploaderBundle\Form\Type\VichFileType;

class MultimediaType extends AbstractType
{
    /**
     * {@inheritdoc}
     */
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('title', null, array(
                'required' => true,
                'label' => 'multimedia.form.title',
                'label_attr' => array('class' => ''),
                'attr' => array('class' => '', 'placeholder' => 'multimedia.form.title')
            ))
            ->add('urlVideo', null, array(
                'required' => false,
                'label' => 'multimedia.form.url_video',
                'label_attr' => array('class' => ''),
                'attr' => array('class' => '', 'placeholder' => 'multimedia.form.url_video_placeholder',)
            ))
            ->add('fileVich', VichFileType::class, array(
                'required' => false,
                'allow_delete' => true,
                'label' => false,
                'label_attr' => array('class' => ''),
                'attr' => array('class' => '')
            ))
            ->add('imageVich', VichFileType::class, array(
                'required' => true,
                'allow_delete' => true,
                'label' => false,
                'label_attr' => array('class' => ''),
                'attr' => array('class' => '')
            ))
            ->add('category', 'entity', array(
                'class' => 'AppBundle\Entity\MultimediaCategory',
                'required' => true,
                'label' => 'multimedia.form.category',
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
            'data_class' => 'AppBundle\Entity\Multimedia'
        ));
    }

    /**
     * {@inheritdoc}
     */
    public function getBlockPrefix()
    {
        return 'appbundle_multimedia';
    }


}
