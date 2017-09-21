<?php

namespace AppBundle\Form;

use AppBundle\Entity\CreativityProposal;
use Symfony\Component\Form\Extension\Core\Type\CollectionType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\Form\FormError;
use Symfony\Component\Form\FormEvent;
use Symfony\Component\Form\FormEvents;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Validator\Constraints\NotNull;

class CreativityProposalType extends AbstractType
{
    /**
     * {@inheritdoc}
     */
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('support', 'choice', array(
                'mapped' => true,
                'required' => true,
                'label' => 'creativity.form.support',
                'choices' => CreativityProposal::getSelectSupports(),
            ))
            ->add('promotion', null, array(
                'label' => 'creativity_proposal.form.promotion',
                'required' => true,
                'label_attr' => array('class' => ''),
                'attr' => array('class' => '')
            ))
            ->add('fileDocs', CollectionType::class, array(
                'entry_type' => 'AppBundle\Form\CreativityProposalFileType',
                'allow_add' => true,
                'required' => true,
                'label' => 'creativity_proposal.form.file_doc',
                'by_reference' => false,
                'allow_delete' => true,
                'label_attr' => array('class' => ''),
                'attr' => array('class' => 'collection row required-label'),
                'constraints' => array( new NotNull())
            ))
            ->add('detail', 'BackendBundle\Form\Type\CKeditorType', array(
                'label' => 'creativity_proposal.form.detail',
                'required' => false,
                'label_attr' => array('class' => ''),
                'attr' => array('class' => '')
            ))
            ->add('quantity', null, array(
                'label' => 'creativity_proposal.form.quantity',
                'required' => true,
                'label_attr' => array('class' => ''),
                'attr' => array('class' => '')
            ))
            ->add('delivery', 'checkbox', array(
                'label' => 'creativity_proposal.form.delivery',
                'required' => false,
                'label_attr' => array('class' => ''),
                'attr' => array('class' => '')
            ))
            ->add('deliveryDetail',  'BackendBundle\Form\Type\CKeditorType', array(
                'label' => 'creativity_proposal.form.delivery_detail',
                'required' => false,
                'label_attr' => array('class' => ''),
                'attr' => array('class' => '')
            ))
            ->add('latitude', null, array(
                'label' => 'creativity_proposal.form.latitude',
                'required' => false,
                'label_attr' => array('class' => ''),
                'attr' => array('class' => '')
            ))->add('longitude', null, array(
                'label' => 'creativity_proposal.form.longitude',
                'required' => false,
                'label_attr' => array('class' => ''),
                'attr' => array('class' => '')
            ))
        ;

        $listener_pre_submit = function (FormEvent $event) {
            $creativityProposalData = $event->getData();
            $form = $event->getForm();

            if(!$creativityProposalData) {
                return;
            }

            //Empty variables when delivery is false
            if(empty($creativityProposalData['delivery'])){
                $creativityProposalData['latitude'] = '';
                $creativityProposalData['longitude'] = '';
                $creativityProposalData['deliveryDetail'] = '';
                $event->setData($creativityProposalData);
            }
            //Validate Delivery true and latitude not blank
            if(!empty($creativityProposalData['delivery']) && empty($creativityProposalData['latitude'])) {
                $form->addError(new FormError('validator.must_not_be_blank', null, array(), null, 'latitude'));
            }
            //Validate Delivery true and longitude not blank
            if(!empty($creativityProposalData['delivery']) && empty($creativityProposalData['longitude'])){
                $form->addError(new FormError('validator.must_not_be_blank', null, array(), null, 'longitude'));
            }
            //Validate at least one image
            if(empty($creativityProposalData['fileDocs'])) {
                $form->addError(new FormError('validator.must_have_one_image', null, array(), null, 'fileDocs'));
            }
        };

        $builder->addEventListener(FormEvents::PRE_SUBMIT, $listener_pre_submit);
    }
    
    /**
     * {@inheritdoc}
     */
    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults(array(
            'data_class' => 'AppBundle\Entity\CreativityProposal'
        ));
    }

    /**
     * {@inheritdoc}
     */
    public function getBlockPrefix()
    {
        return 'appbundle_creativityproposal';
    }


}
