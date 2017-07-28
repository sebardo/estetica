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
//			->add('password', 'Symfony\Component\Form\Extension\Core\Type\PasswordType', array(
//				'label' => 'user.password',
//				'required' => $options['required_password']
//			))
			->add('tradeName')
			->add('tagLine')
			->add('shortDescription')
			->add('description')
			->add('technology')
			->add('societyName')
			->add('socialNumber')
			->add('nif')
			->add('urlWeb')
			->add('facebook')
			->add('instagram')
			->add('blog')
			->add('logoFile', VichFileType::class, array(
				'required' => true,
				'allow_delete' => true
			))
			//
			->add('fileDocs', CollectionType::class, array(
				'entry_type' => 'AppBundle\Form\FileDocType',
				'allow_add' => true,
				'label' => 'client.file_doc',
				'by_reference' => false,
				'allow_delete' => true,
				'attr' => array('class' => 'collection row required-label')
			))
			->add('plan', null, array(
				'required' => true
			))
			->add('billingAddress', new AddressType(), array(
				'required' => true
			))
			->add('localAddress', new AddressType(), array(
				'required' => true
			))
		;
	}

	/**
	 * {@inheritdoc}
	 */
	public function configureOptions(OptionsResolver $resolver)
	{
		$resolver->setDefaults(array(
			'data_class' => 'AppBundle\Entity\Client',
			'required_password' => true
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