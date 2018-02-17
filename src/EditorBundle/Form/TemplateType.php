<?php

namespace EditorBundle\Form;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Form\Extension\Core\Type\FileType;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\Extension\Core\Type\HiddenType;

class TemplateType extends AbstractType
{
    
    /**
     * {@inheritdoc}
     */
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
                ->add('name', null, array('required' => false,  'label' => 'template.name'))
                ->add('backgroundImageFile', FileType::class, array('required' => false,  'label' => 'template.background1'))  
                ->add('backgroundImage2File', FileType::class, array('required' => false,  'label' => 'template.background2'))
                ->add('support', 'choice', array(
                    'mapped' => true,
                    'required' => true,
                    'label' => 'creativity.form.support',
                    'choices' => array_merge($this->getSelectSupports(),$this->getSelectSupportsPost()),
                ))
                ->add('category', 'choice', array(
                    'mapped' => true,
                    'required' => true,
                    'label' => 'creativity.form.category',
                    'choices' => $this->getSelectCategories(),
                ))
                ->add('subcategory', 'choice', array(
                    'mapped' => true,
                    'required' => true,
                    'label' => 'creativity.form.subcategory',
                    'choices' => $this->getSelectSubcategories('all')
                ))
                ->add('frontPageHtml', TextareaType::class, array('required' => false))
                ->add('backPageHtml', TextareaType::class, array('required' => false))
                ;
    }
    
    /**
     * {@inheritdoc}
     */
    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults(array(
            'data_class' => 'EditorBundle\Entity\Template',
        ));
    }

    public static function getSelectSupports()
    {
        return array(
            'flyers' => 'app.support.flyers',
            'routers' => 'app.support.routers',
            'roll-up' => 'app.support.rollup',
        );
    }
    
    public static function getSelectSupportsPost()
    {
        return array(
            'facebook_1' => 'app.support.post.facebook_1',
            'facebook_2' => 'app.support.post.facebook_2',
            'facebook_3' => 'app.support.post.facebook_3',
            'social' => 'app.support.post.social',
            'instagram' => 'app.support.post.instagram',
            'pinterest' => 'app.support.post.pinterest',
        );
    }
    
    public static function getSelectCategories()
    {
        return array(
            'technology' => 'app.category.technology.name',
            'month' => 'app.category.month.name',
            'campaign' => 'app.category.campaign.name',
            'pack' => 'app.category.pack.name'
        );
    }
    
    public static function getSelectSubcategories($category=null)
    {
        switch($category){
            case 'technology':
                $response = array(
                    'criolopolisis' => 'app.category.technology.1.name',
                    'ipl-fotodepilacion' => 'app.category.technology.2.name',
                    'laser-depilacion' => 'app.category.technology.3.name',
                    'radiofrecuencia' => 'app.category.technology.4.name',
                    'cavitacion' => 'app.category.technology.5.name',
                    'hifu' => 'app.category.technology.6.name',
                    'narl' => 'app.category.technology.7.name'
                );
                break;
            case 'month':
                $response = array(
                    'enero' => 'app.category.month.1.name',
                    'febrero' => 'app.category.month.2.name',
                    'marzo' => 'app.category.month.3.name',
                    'abril' => 'app.category.month.4.name',
                    'mayo' => 'app.category.month.5.name',
                    'junio' => 'app.category.month.6.name',
                    'julio' => 'app.category.month.7.name',
                    'agosto' => 'app.category.month.8.name',
                    'septiembre' => 'app.category.month.9.name',
                    'octubre' => 'app.category.month.10.name',
                    'noviembre' => 'app.category.month.11.name',
                    'diciembre' => 'app.category.month.12.name'
                );
                break;
            case 'campaign':
                $response = array(
                    'dia-padre' => 'app.category.campaign.1.name',
                    'dia-madre' => 'app.category.campaign.2.name',
                    'san-valentin' => 'app.category.campaign.3.name',
                    'verano' => 'app.category.campaign.4.name',
                    'navidad' => 'app.category.campaign.5.name',
                    'otras' => 'app.category.campaign.6.name'
                );
                break;
            case 'all':
                $response = array(
                    'criolopolisis' => 'app.category.technology.1.name',
                    'ipl-fotodepilacion' => 'app.category.technology.2.name',
                    'laser-depilacion' => 'app.category.technology.3.name',
                    'radiofrecuencia' => 'app.category.technology.4.name',
                    'cavitacion' => 'app.category.technology.5.name',
                    'hifu' => 'app.category.technology.6.name',
                    'narl' => 'app.category.technology.7.name',
                    'enero' => 'app.category.month.1.name',
                    'febrero' => 'app.category.month.2.name',
                    'marzo' => 'app.category.month.3.name',
                    'abril' => 'app.category.month.4.name',
                    'mayo' => 'app.category.month.5.name',
                    'junio' => 'app.category.month.6.name',
                    'julio' => 'app.category.month.7.name',
                    'agosto' => 'app.category.month.8.name',
                    'septiembre' => 'app.category.month.9.name',
                    'octubre' => 'app.category.month.10.name',
                    'noviembre' => 'app.category.month.11.name',
                    'diciembre' => 'app.category.month.12.name',
                    'dia-padre' => 'app.category.campaign.1.name',
                    'dia-madre' => 'app.category.campaign.2.name',
                    'san-valentin' => 'app.category.campaign.3.name',
                    'verano' => 'app.category.campaign.4.name',
                    'navidad' => 'app.category.campaign.5.name',
                    'otras' => 'app.category.campaign.6.name'
                );
                break;
            default:
                $response = array();
                break;
        }
        
        return $response;
    }
    
}
