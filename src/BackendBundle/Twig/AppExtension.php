<?php


namespace BackendBundle\Twig;

use Symfony\Component\DependencyInjection\ContainerInterface;
use Symfony\Component\Validator\Constraints\DateTime;


class AppExtension extends \Twig_Extension {

	/** @var ContainerInterface */
	protected $container;

	/** @param ContainerInterface $container */
	public function __construct(ContainerInterface $container)
	{
		$this->container = $container;
	}

	public function getFilters()
	{
		return array(
			new \Twig_SimpleFilter('isDateTime', array($this, 'isDateTime')),
		);
	}

	public function isDateTime($object)
	{
		return $object instanceof \DateTime;
	}

	/**
	 * Returns the name of the extension.
	 *
	 * @return string The extension name
	 */
	public function getName()
	{
		return 'app_extension';
	}
}