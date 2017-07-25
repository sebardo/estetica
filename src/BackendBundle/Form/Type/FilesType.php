<?php


namespace BackendBundle\Form\Type;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\Form\FormInterface;
use Symfony\Component\Form\FormView;
use Symfony\Component\Form\Tests\Extension\Core\Type\CollectionTypeTest;
use Symfony\Component\OptionsResolver\Options;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Validator\Constraints\Collection;
use Symfony\Component\Form\Extension\Core\Type\CollectionType;

class FilesType extends AbstractType
{
    /**
     * Returns the name of this type.
     *
     * @return string The name of this type
     */
    public function getBlockPrefix()
    {
        return 'files_type';
    }

    /**
     * {@inheritdoc}
     */
    public function buildView(FormView $view, FormInterface $form, array $options)
    {
        $view->vars['url_upload'] = $options['url_upload'];
        $view->vars['path_copy'] = $options['path_copy'];
        $view->vars['url_file'] = $options['url_file'];
        $view->vars['attr'] = array('style' => 'opacity: 0;width: 0; max-width: 0; height: 0; max-height: 0;');

        $acceptFileTypes=$options['acceptFileTypes'];
        if ($acceptFileTypes) {
            $aux = $acceptFileTypes[0];
            for ($i = 1; $i < count($acceptFileTypes); $i++){
                $aux.='|'.$acceptFileTypes[$i];
            }
            $acceptFileTypes=' /(\.|\/)('.$aux.')$/i';
        } else {
            $acceptFileTypes='null';
        }
        $view->vars['acceptFileTypes'] = $acceptFileTypes;

    }

    /**
     * Configures the options for this type.
     *
     * @param OptionsResolver $resolver The resolver for the options
     */
    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefined(
            [
                'url_upload',//url upload controller
                'path_copy',//path copied files
                'url_file'//url show file
            ]
        );
        $resolver->setDefaults(array(
            'acceptFileTypes' => null,
            'allow_add' => true,
            'allow_delete' => true,

        ));
    }

    public function getParent()
    {
         return CollectionType::class;
    }


}