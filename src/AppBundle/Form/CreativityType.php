<?php

namespace AppBundle\Form;

use AppBundle\Entity\Creativity;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\Form\FormError;
use Symfony\Component\Form\FormEvent;
use Symfony\Component\Form\FormEvents;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Form\Extension\Core\Type\CollectionType;
use Symfony\Component\Validator\Constraints\NotBlank;
use Symfony\Component\Validator\Constraints\NotNull;

class CreativityType extends AbstractType
{
    /**
     * {@inheritdoc}
     */
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('name', null, array(
                'label' => 'creativity.form.name',
            ))
            ->add('support', 'choice', array(
                'mapped' => true,
                'required' => true,
                'label' => 'creativity.form.support',
                'choices' => Creativity::getSelectSupports(),
            ))
            ->add('category', 'choice', array(
                'mapped' => true,
                'required' => true,
                'label' => 'creativity.form.category',
                'choices' => Creativity::getSelectCategories(),
            ))
            ->add('subcategory', 'choice', array(
                'mapped' => true,
                'required' => true,
                'label' => 'creativity.form.subcategory',
                'choices' => Creativity::getSelectSubcategories(Creativity::CATEGORY_TECHNOLOGY)
            ))
            ->add('fileDocs', CollectionType::class, array(
                'entry_type' => 'AppBundle\Form\CreativityFileType',
                'allow_add' => true,
                'required' => ($options['edit_form']) ? false : true,
                'label' => 'creativity.form.file_doc',
                'by_reference' => false,
                'allow_delete' => true,
                'label_attr' => array('class' => ''),
                'attr' => array('class' => 'collection row required-label'),
                'constraints' => array( new NotNull())
            ))
            ->add('fileDocsRaw', CollectionType::class, array(
                'entry_type' => 'AppBundle\Form\CreativityFileRawType',
                'allow_add' => true,
                'required' => ($options['edit_form']) ? false : true,
                'label' => 'creativity.form.file_doc_raw',
                'by_reference' => false,
                'allow_delete' => true,
                'label_attr' => array('class' => ''),
                'attr' => array('class' => 'collection row required-label'),
                'constraints' => array( new NotNull())
            ))
        ;

        $listener = function (FormEvent $event) {
            $creativityData = $event->getData();
            $form = $event->getForm();

            if(!$creativityData) {
                return;
            }

            if($creativityData['support'] === Creativity::SUPPORT_FLYERS){
                if(empty($creativityData['fileDocs']) || count($creativityData['fileDocs']) <= 2) {
                    $form->addError(new FormError('validator.must_have_two_image', null, array(), null, 'fileDocs'));
                }
                if(empty($creativityData['fileDocsRaw']) || count($creativityData['fileDocsRaw']) <= 2) {
                    $form->addError(new FormError('validator.must_have_two_image', null, array(), null, 'fileDocsRaw'));
                }
            }else {
                if(empty($creativityData['fileDocs'])) {
                    $form->addError(new FormError('validator.must_have_one_image', null, array(), null, 'fileDocs'));
                }
                if(empty($creativityData['fileDocsRaw'])) {
                    $form->addError(new FormError('validator.must_have_one_image', null, array(), null, 'fileDocsRaw'));
                }
            }
        };
        $builder->addEventListener(FormEvents::PRE_SUBMIT, $listener);
    }
    
    /**
     * {@inheritdoc}
     */
    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults(array(
            'data_class' => 'AppBundle\Entity\Creativity',
            'edit_form' => false
        ));
    }

    /**
     * {@inheritdoc}
     */
    public function getBlockPrefix()
    {
        return 'appbundle_creativity';
    }


}
