<?php

namespace AppBundle\Form;

use AppBundle\Form\EventListener\AddSpecialityFieldSubscriber;
use AppBundle\Form\Registration\BooleanType;
use AppBundle\Form\Registration\CourseType;
use AppBundle\Form\Registration\LanguageType;
use AppBundle\Form\Registration\PlaceResidenceType;
use AppBundle\Form\Registration\GenderType;
use Symfony\Component\DependencyInjection\ContainerInterface;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Vich\UploaderBundle\Form\Type\VichImageType;

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
            ->add('birthday', null, array(
                'label' => 'registration.form.birthday.name',
                'required' => true,
                'label_attr' => array('class' => ''),
                'attr' => array('class' => '')
            ))
            ->add('imageFile', VichImageType::class, array(
                'required' => true,
                'allow_delete' => true,
                'label' => 'registration.form.image.name',
                'label_attr' => array('class' => ''),
                'attr' => array('class' => '')
            ))
            //Speciality
                //Events
            //Experience
            ->add('experience', BooleanType::class, array(
                'label' => 'registration.form.experience.name',
                'required' => true,
                'label_attr' => array('class' => ''),
                'attr' => array('class' => '')
            ))
            //Language
                //Events
            ->add('language', new LanguageType($this->container->get('webapp.manager.language_manager')), array(
                'label' => 'registration.form.language.name',
                'required' => true,
                'label_attr' => array('class' => ''),
                'attr' => array('class' => ''),
                'mapped' => false
            ))
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
//                'placeholder' => 'registration.form.address.default',
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
            ->add('course', new CourseType($this->container->get('webapp.manager.course_manager')), array(
                'label' => 'registration.form.course.name',
                'required' => true,
                'label_attr' => array('class' => ''),
                'attr' => array('class' => ''),
                'mapped' => false
            ))
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
            'data_class' => 'AppBundle\Entity\Registration'
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
