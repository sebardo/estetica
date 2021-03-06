<?php

namespace AppBundle\Form;

use AppBundle\Form\EventListener\AddCityFieldSubscriber;
use AppBundle\Form\EventListener\AddCountryFieldSubscriber;
use AppBundle\Form\EventListener\AddProvinceFieldSubscriber;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class AddressType extends AbstractType
{
    /**
     * {@inheritdoc}
     */
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $factory = $builder->getFormFactory();
        $countrySubscriber = new AddCountryFieldSubscriber($factory, $options);
        $builder->addEventSubscriber($countrySubscriber);
        $provinceSubscriber = new AddProvinceFieldSubscriber($factory, $options);
        $builder->addEventSubscriber($provinceSubscriber);
        $citySubscriber = new AddCityFieldSubscriber($factory, $options);
        $builder->addEventSubscriber($citySubscriber);

        $builder
            ->add('address', null, array(
                'required' => $options['required_form'],
                'label' => 'address.form.address',
                'label_attr' => array('class' => ''),
                'attr' => array('class' => '')
            ))
            ->add('addressInfo', null, array(
                'required' => $options['required_form'],
                'label' => 'address.form.addressinfo',
                'label_attr' => array('class' => ''),
                'attr' => array('class' => '')
            ))
            ->add('phone', null, array(
                'required' => true,
                'label' => 'address.form.phone',
                'label_attr' => array('class' => ''),
                'attr' => array('class' => '')
            ))
            ->add('email', null, array(
                'required' => true,
                'label' => 'address.form.email',
                'label_attr' => array('class' => ''),
                'attr' => array('class' => '')
            ))
            ->add('contact', null, array(
                'required' => true,
                'label' => 'address.form.contact',
                'label_attr' => array('class' => ''),
                'attr' => array('class' => '')
            ))
            ->add('postalCode', null, array(
                'required' => false,
                'label' => 'address.form.postal_code',
                'label_attr' => array('class' => ''),
                'attr' => array('class' => '')
            ))
            ->add('whatsapp', null, array(
                'label' => 'Whatsapp',
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
            'data_class' => 'AppBundle\Entity\Address',
            'required_form' => false
        ));
    }

    /**
     * {@inheritdoc}
     */
    public function getBlockPrefix()
    {
        return 'appbundle_address';
    }
}
