<?php

namespace EditorBundle\Form;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\Form\FormError;
use Symfony\Component\Form\FormEvent;
use Symfony\Component\Form\FormEvents;
use Symfony\Component\OptionsResolver\OptionsResolver;

class TemplateDeliveryType extends AbstractType
{
    /**
     * {@inheritdoc}
     */
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('delivery', 'checkbox', array(
                'label' => 'creativity_order.form.delivery',
                'required' => false,
                'label_attr' => array('class' => ''),
                'attr' => array('class' => '')
            ))
            ->add('deliveryDetail',  'BackendBundle\Form\Type\CKeditorType', array(
                'label' => 'creativity_order.form.delivery_detail',
                'required' => false,
                'label_attr' => array('class' => ''),
                'attr' => array('class' => '')
            ))
            ->add('latitude', null, array(
                'label' => 'creativity_order.form.latitude',
                'required' => false,
                'label_attr' => array('class' => ''),
                'attr' => array('class' => '')
            ))->add('longitude', null, array(
                'label' => 'creativity_order.form.longitude',
                'required' => false,
                'label_attr' => array('class' => ''),
                'attr' => array('class' => '')
            ))
        ;

        $listener_pre_submit = function (FormEvent $event) {
            $creativityOrderData = $event->getData();
            $form = $event->getForm();

            if(!$creativityOrderData) {
                return;
            }

            //Empty variables when delivery is false
            if(empty($creativityOrderData['delivery'])){
                $creativityOrderData['latitude'] = '';
                $creativityOrderData['longitude'] = '';
                $creativityOrderData['deliveryDetail'] = '';
                $event->setData($creativityOrderData);
            }
            //Validate Delivery true and latitude not blank
            if(!empty($creativityOrderData['delivery']) && empty($creativityOrderData['latitude'])) {
                $form->addError(new FormError('validator.must_not_be_blank', null, array(), null, 'latitude'));
            }
            //Validate Delivery true and longitude not blank
            if(!empty($creativityOrderData['delivery']) && empty($creativityOrderData['longitude'])){
                $form->addError(new FormError('validator.must_not_be_blank', null, array(), null, 'longitude'));
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
            'data_class' => 'EditorBundle\Entity\Template'
        ));
    }

}
