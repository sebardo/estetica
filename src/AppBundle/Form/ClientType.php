<?php

namespace AppBundle\Form;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\CollectionType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Vich\UploaderBundle\Form\Type\VichFileType;

class ClientType extends AbstractType
{
	/**
	 * {@inheritdoc}
	 */
	public function buildForm(FormBuilderInterface $builder, array $options)
	{
		$builder
			->add('tradeName', null, array(
				'label' => 'client.form.trade_name',
				'required' => true,
				'label_attr' => array('class' => ''),
				'attr' => array('class' => '')
			))
			->add('tagLine', null, array(
				'label' => 'client.form.tag_line',
				'required' => true,
				'label_attr' => array('class' => ''),
				'attr' => array('class' => '')
			))
			->add('shortDescription', 'BackendBundle\Form\Type\CKeditorType', array(
				'label' => 'client.form.short_description',
				'required' => true,
				'label_attr' => array('class' => ''),
				'attr' => array('class' => '')
			))
			->add('description', 'BackendBundle\Form\Type\CKeditorType', array(
				'label' => 'client.form.description',
				'required' => true,
				'label_attr' => array('class' => ''),
				'attr' => array('class' => '')
			))
			->add('technology', 'BackendBundle\Form\Type\CKeditorType', array(
				'label' => 'client.form.technology',
				'required' => true,
				'label_attr' => array('class' => ''),
				'attr' => array('class' => '')
			))
			->add('societyName', 'BackendBundle\Form\Type\CKeditorType', array(
				'label' => 'client.form.society_name',
				'required' => true,
				'label_attr' => array('class' => ''),
				'attr' => array('class' => '')
			))
			->add('socialNumber', null, array(
				'label' => 'client.form.social_number',
				'required' => true,
				'label_attr' => array('class' => ''),
				'attr' => array('class' => '')
			))
			->add('nif', null, array(
				'label' => 'client.form.nif',
				'required' => true,
				'label_attr' => array('class' => ''),
				'attr' => array('class' => '')
			))
			->add('urlWeb', null, array(
				'label' => 'client.form.url_web',
				'required' => true,
				'label_attr' => array('class' => ''),
				'attr' => array('class' => '')
			))
			->add('facebook', null, array(
				'label' => 'client.form.facebook',
				'required' => true,
				'label_attr' => array('class' => ''),
				'attr' => array('class' => '')
			))
			->add('instagram', null, array(
				'label' => 'client.form.instagram',
				'required' => true,
				'label_attr' => array('class' => ''),
				'attr' => array('class' => '')
			))
			->add('blog', null, array(
				'label' => 'client.form.blog',
				'required' => true,
				'label_attr' => array('class' => ''),
				'attr' => array('class' => '')
			))
//			->add('logoFile', 'BackendBundle\Form\Type\DocumentType', array(
//				'required' => true,
//				//'allow_delete' => false,
//				'url_upload' => 'client_upload_image',
//				'url_show' => '',
//				'acceptFileTypes' => array('jpg','png','gif','jpeg'),
//				'label' => 'client.form.logo',
//				'label_attr' => array('class' => ''),
//				'attr' => array('class' => '')
//			))
			->add('logoFile', 'Vich\UploaderBundle\Form\Type\VichImageType', array(
				'required' => true,
				'allow_delete' => true,
				'label' => 'client.form.logo',
				'label_attr' => array('class' => ''),
				'attr' => array('class' => '')
			))
			->add('fileDocs', CollectionType::class, array(
				'entry_type' => 'AppBundle\Form\FileDocType',
				'allow_add' => true,
				'label' => 'client.form.file_doc',
				'by_reference' => false,
				'allow_delete' => true,
				'label_attr' => array('class' => ''),
				'attr' => array('class' => 'collection row required-label')
			))
			->add('plan', null, array(
				'label' => 'client.form.plan',
				'required' => true,
				'label_attr' => array('class' => ''),
				'attr' => array('class' => '')
			))
			->add('billingAddress', new AddressType(), array(
				'label' => 'client.form.billing_address',
				'required' => true,
				'label_attr' => array('class' => ''),
				'attr' => array('class' => ''),
				'required_form' => true
			))
			->add('localAddress', new AddressType(), array(
				'label' => 'client.form.local_address',
				'required' => true,
				'label_attr' => array('class' => ''),
				'attr' => array('class' => ''),
				'required_form' => false
			))
		;

		if($options['edit_form'] === true){
			$builder
				->add('password', 'Symfony\Component\Form\Extension\Core\Type\PasswordType', array(
					'label' => 'client.form.new_password',
					'label_attr' => array('class' => ''),
					'attr' => array('class' => ''),
					'required' => true
				))
			;
		}
	}

	/**
	 * {@inheritdoc}
	 */
	public function configureOptions(OptionsResolver $resolver)
	{
		$resolver->setDefaults(array(
			'data_class' => 'AppBundle\Entity\Client',
			'edit_form' => false
		));
	}

	/**
	 * {@inheritdoc}
	 */
	public function getBlockPrefix()
	{
		return 'appbundle_client';
	}
}