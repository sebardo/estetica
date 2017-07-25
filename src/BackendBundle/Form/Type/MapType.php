<?php


namespace BackendBundle\Form\Type;

use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormInterface;
use Symfony\Component\Form\FormView;
use Symfony\Component\OptionsResolver\OptionsResolver;

class MapType extends AbstractType
{
    /**
     * Returns the name of this type.
     *
     * @return string The name of this type
     */
    public function getBlockPrefix()
    {
        return 'map';
    }

    public function getParent()
    {
        return  'Symfony\Component\Form\Extension\Core\Type\TextType';
    }

    /**
     * {@inheritdoc}
     */
    public function buildView(FormView $view, FormInterface $form, array $options)
    {
        $view->vars['lat_field'] = $options['lat_field'];
        $view->vars['lng_field'] = $options['lng_field'];
        $view->vars['map_id'] = $options['map_id'];
        $view->vars['token'] = $options['token'];
        $view->vars['soon'] = $options['soon'];
        $view->vars['url'] = $options['url'];
        $view->vars['imagePath'] = $options['imagePath'];
        $view->vars['height'] = $options['height'];
        $view->vars['marker'] = $options['marker'];
    }

    /**
     * Configures the options for this type.
     *
     * @param OptionsResolver $resolver The resolver for the options
     */
    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefined(array(
                'lat_field',//field name latitud from entity
                'lng_field',//field name longitud from entity
                'map_id',//type map for show: mapbox.streets, etc...
                'token',//token of user account in mapbox
                'soon',//distance to Earth
                'url',//url for get map
                'imagePath',//path images icons
                'height',//value height container map
                'marker'//style marker
            )
        );
    }
}