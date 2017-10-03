<?php

namespace AppBundle\Form;

use AppBundle\Entity\Client;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\CollectionType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\Form\FormEvent;
use Symfony\Component\Form\FormEvents;
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
			->add('societyName', 'text', array(
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
				'required' => false,
				'label_attr' => array('class' => ''),
				'attr' => array('class' => '')
			))
			->add('facebook', null, array(
				'label' => 'client.form.facebook',
				'required' => false,
				'label_attr' => array('class' => ''),
				'attr' => array('class' => '')
			))
			->add('instagram', null, array(
				'label' => 'client.form.instagram',
				'required' => false,
				'label_attr' => array('class' => ''),
				'attr' => array('class' => '')
			))
			->add('blog', null, array(
				'label' => 'client.form.blog',
				'required' => false,
				'label_attr' => array('class' => ''),
				'attr' => array('class' => '')
			))
			->add('logoFile', 'Vich\UploaderBundle\Form\Type\VichImageType', array(
				'required' => ($options['edit_form']) ? false : true,
				'allow_delete' => false,
				'label' => 'client.form.logo',
				'label_attr' => array('class' => ''),
				'attr' => array('class' => '')
			))
			->add('fileDocs', CollectionType::class, array(
				'entry_type' => 'AppBundle\Form\FileDocType',
				'allow_add' => true,
				'required' => ($options['edit_form']) ? false : true,
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
				'attr' => array('class' => ''),
				'disabled' => ($options['admin_user']) ? false : 'disabled'
			))
			->add('billingAddress', new AddressType(), array(
				'label' => 'client.form.billing_address',
				'required' => true,
				'label_attr' => array('class' => ''),
				'attr' => array('class' => ''),
				'required_form' => true
			))
			->add('localAddress', new LocalAddressType(), array(
				'label' => 'client.form.local_address',
				'required' => false,
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
					'required' => false
				))
			;
		}

		$listener = function (FormEvent $event) {
			$clientData = $event->getData();
			$form = $event->getForm();

			if(!$clientData) {
				return;
			}

			if(empty($clientData['localAddress']['address'])) {
				$clientData['localAddress']['address'] = $clientData['billingAddress']['address'];
				$clientData['localAddress']['phone'] = $clientData['billingAddress']['phone'];
				$clientData['localAddress']['email'] = $clientData['billingAddress']['email'];
				$clientData['localAddress']['contact'] = $clientData['billingAddress']['contact'];
				$clientData['localAddress']['postalCode'] = $clientData['billingAddress']['postalCode'];
				$clientData['localAddress']['country'] = $clientData['billingAddress']['country'];
				$clientData['localAddress']['province'] = $clientData['billingAddress']['province'];
				$clientData['localAddress']['city'] = $clientData['billingAddress']['city'];
				$event->setData($clientData);
			}
		};
		$builder->addEventListener(FormEvents::PRE_SUBMIT, $listener);
	}

	/**
	 * {@inheritdoc}
	 */
	public function configureOptions(OptionsResolver $resolver)
	{
		$resolver->setDefaults(array(
			'data_class' => 'AppBundle\Entity\Client',
			'edit_form' => false,
			'admin_user' => true
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