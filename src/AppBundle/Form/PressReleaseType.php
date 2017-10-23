<?php

namespace AppBundle\Form;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class PressReleaseType extends AbstractType
{
    /**
     * {@inheritdoc}
     */
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('title', null, array(
                'label' => 'press_release.form.title.name',
                'required' => true,
                'label_attr' => array('class' => ''),
                'attr' => array('class' => '')
            ))
            ->add('published', 'datetime', array(
                'label' => 'press_release.form.published.name',
                'required' => true,
                'label_attr' => array('class' => ''),
                'attr' => array('class' => 'datepicker'),
                'widget' => 'single_text',
                'html5' => false
            ))
            ->add('description', 'BackendBundle\Form\Type\CKeditorType', array(
                'label' => 'press_release.form.description.name',
                'required' => true,
                'label_attr' => array('class' => ''),
                'attr' => array('class' => '')
            ))
            ->add('documentFile', 'Vich\UploaderBundle\Form\Type\VichFileType', array(
                'required' => false, //($options['edit_form']) ? false : true,
                'allow_delete' => false,
                'label' => 'press_release.form.document.name',
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
            'data_class' => 'AppBundle\Entity\PressRelease',
            'edit_form' => false
        ));
    }

    /**
     * {@inheritdoc}
     */
    public function getBlockPrefix()
    {
        return 'appbundle_pressrelease';
    }


}
