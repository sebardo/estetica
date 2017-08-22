<?php

namespace AppBundle\Twig;

use AppBundle\Entity\Client;
use AppBundle\Entity\FileDoc;
use AppBundle\Services\Slugify;
use Symfony\Component\DependencyInjection\ContainerInterface;

class RegistrationExtension extends \Twig_Extension
{
	private $container;

	public function __construct(ContainerInterface $containerInterface)
	{
		$this->container = $containerInterface;
	}
	/**
	 * {@inheritdoc}
	 */
	public function getFunctions()
	{
		return array(
			'get_parent_speciality_collection' => new \Twig_Function_Method($this, 'getParentSpecialityCollection'),
		);
	}

	public function getParentSpecialityCollection()
	{
		$response = array();
		$parentSpecialityCollection = $this->container->get('webapp.manager.parent_speciality_manager')->getBy(array());
		foreach ($parentSpecialityCollection as $parentSpeciality) {
			$response[$parentSpeciality->getId()] = Slugify::slug($parentSpeciality->getFormatName());
		}

		return $response;
	}

	/**
	 * Returns the name of the extension.
	 *
	 * @return string The extension name
	 */
	public function getName()
	{
		return 'registration_extension';
	}
}