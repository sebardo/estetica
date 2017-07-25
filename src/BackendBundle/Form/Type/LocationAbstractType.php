<?php


namespace BackendBundle\Form\Type;

use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\FormInterface;
use Symfony\Component\Form\FormView;
use Symfony\Component\OptionsResolver\OptionsResolver;

abstract class LocationAbstractType extends AbstractType
{
    public function getParent()
    {
        return EntityType::class;
    }

    public function buildView(FormView $view, FormInterface $form, array $options)
    {
        $view->vars['county'] = $options['county'];
        $view->vars['city'] = $options['city'];
        $view->vars['get_city'] = $options['get_city'];
        $view->vars['get_location'] = $options['get_location'];
        $view->vars['location'] = $options['location'];
        $view->vars['county_select'] = $options['county_select'];
        $view->vars['city_select'] = $options['city_select'];

    }

    public function configureOptions(OptionsResolver $resolver)
    {
        parent::configureOptions($resolver);
        $resolver->setDefault('label',false);
        $resolver->setDefined(array(
            'county',
            'city',
            'location',
            'county_select',
            'city_select',
            'get_city',
            'get_location'
        ));
    }
}