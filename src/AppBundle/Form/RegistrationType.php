<?php

namespace AppBundle\Form;

use AppBundle\Entity\Registration;
use AppBundle\Form\EventListener\AddCourseFieldSubscriber;
use AppBundle\Form\EventListener\AddLanguageFieldSubscriber;
use AppBundle\Form\EventListener\AddSpecialityFieldSubscriber;
use AppBundle\Form\Types\BooleanType;
use AppBundle\Form\Types\GenderType;
use AppBundle\Form\Registration\PlaceResidenceType;
use Symfony\Component\DependencyInjection\ContainerInterface;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class RegistrationType extends AbstractType
{
    /** @var ContainerInterface $container */
    private $container;

    public function __construct(ContainerInterface $container)
    {
        $this->container = $container;
    }

    /**
     * {@inheritdoc}
     */
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $specialitySubscriber = new AddSpecialityFieldSubscriber($this->container, $options);
        $builder->addEventSubscriber($specialitySubscriber);
        $courseSubscriber = new AddCourseFieldSubscriber($this->container, $options);
        $builder->addEventSubscriber($courseSubscriber);
        $languageSubscriber = new AddLanguageFieldSubscriber($this->container, $options);
        $builder->addEventSubscriber($languageSubscriber);

        $builder
            //Personal
            ->add('name', null, array(
                'label' => 'registration.form.name.name',
                'required' => true,
                'label_attr' => array('class' => ''),
                'attr' => array('class' => '')
            ))
            ->add('firstLastname', null, array(
                'label' => 'registration.form.first_last_name.name',
                'required' => true,
                'label_attr' => array('class' => ''),
                'attr' => array('class' => '')
            ))
            ->add('secondLastname', null, array(
                'label' => 'registration.form.second_last_name.name',
                'required' => true,
                'label_attr' => array('class' => ''),
                'attr' => array('class' => '')
            ))
            ->add('phone', null, array(
                'label' => 'registration.form.phone.name',
                'required' => true,
                'label_attr' => array('class' => ''),
                'attr' => array('class' => '')
            ))
            ->add('mobile', null, array(
                'label' => 'registration.form.mobile.name',
                'required' => true,
                'label_attr' => array('class' => ''),
                'attr' => array('class' => '')
            ))
            ->add('email', null, array(
                'label' => 'registration.form.email.name',
                'required' => true,
                'label_attr' => array('class' => ''),
                'attr' => array('class' => '')
            ))
            ->add('gender', GenderType::class, array(
                'label' => 'registration.form.gender.name',
                'required' => true,
                'label_attr' => array('class' => ''),
                'attr' => array('class' => '')
            ))
            ->add('birthday', 'date', array(
                'label' => 'registration.form.birthday.name',
                'required' => true,
                'label_attr' => array('class' => ''),
                'attr' => array('class' => ''),
                'widget' => 'single_text'

            ))
            ->add('image', new ImageType(), array(
                'required' => ($options['edit_form']) ? false : true,
                'label' => 'registration.form.image.name',
                'label_attr' => array('class' => ''),
                'attr' => array('class' => '')
            ))
            //Speciality on Event
            //Experience
            ->add('experience', null, array(
                'label' => 'registration.form.experience.name',
                'required' => true,
                'label_attr' => array('class' => ''),
                'attr' => array('class' => '')
            ))
            //Language on Event
            //Vehicle
            ->add('vehicle', BooleanType::class, array(
                'label' => 'registration.form.vehicle.name',
                'required' => true,
                'label_attr' => array('class' => ''),
                'attr' => array('class' => '')
            ))
            //TravelAvailability
            ->add('travelAvailability', BooleanType::class, array(
                'label' => 'registration.form.travel_availability.name',
                'required' => true,
                'label_attr' => array('class' => ''),
                'attr' => array('class' => '')
            ))
            //TimeAvailability
            ->add('timesAvailability', null, array(
                'label' => 'registration.form.time_availability.name',
                'label_attr' => array('class' => ''),
                'attr' => array('class' => ''),
                'required' => true,
                'expanded' => true,
                'multiple' => true
            ))
            //Disability
            ->add('certificateDisability', BooleanType::class, array(
                'label' => 'registration.form.certificate_disability.name',
                'required' => true,
                'label_attr' => array('class' => ''),
                'attr' => array('class' => '')
            ))
            //SalesTraining
            ->add('salesTraining', BooleanType::class, array(
                'label' => 'registration.form.sales_training.name',
                'required' => true,
                'label_attr' => array('class' => ''),
                'attr' => array('class' => '')
            ))
            //ContractType
            ->add('contractTypes', null, array(
                'label' => 'registration.form.contract_type.name',
                'required' => true,
                'label_attr' => array('class' => ''),
                'attr' => array('class' => ''),
                'expanded' => true,
                'multiple' => true
            ))
            //LevelResponsibility
            ->add('levelsResponsibility', null, array(
                'label' => 'registration.form.level_responsibility.name',
                'required' => true,
                'label_attr' => array('class' => ''),
                'attr' => array('class' => ''),
                'expanded' => true,
                'multiple' => true
            ))
            //Address PlaceResidence
            ->add('placeResidence', PlaceResidenceType::class, array(
                'label' => 'registration.form.address.name',
                'required' => true,
                'label_attr' => array('class' => ''),
                'attr' => array('class' => '')
            ))
            //Studies
            ->add('studies', null, array(
                'label' => 'registration.form.study.name',
                'required' => true,
                'label_attr' => array('class' => ''),
                'attr' => array('class' => ''),
                'expanded' => true,
                'multiple' => true
            ))
            //SpecialityDetails
                //Event
            //Academic Studies
            ->add('academicStudies' , null, array(
                'label' => 'registration.form.academic_study.name',
                'required' => true,
                'label_attr' => array('class' => ''),
                'attr' => array('class' => ''),
                'expanded' => true,
                'multiple' => true
            ))
        ;
    }
    
    /**
     * {@inheritdoc}
     */
    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults(array(
            'data_class' => 'AppBundle\Entity\Registration',
            'edit_form' => false
        ));
    }

    /**
     * {@inheritdoc}
     */
    public function getBlockPrefix()
    {
        return 'appbundle_registration';
    }


}
