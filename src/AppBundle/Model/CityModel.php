<?php


namespace AppBundle\Model;


use Doctrine\ORM\EntityRepository;
use Symfony\Component\DependencyInjection\ContainerInterface;
use Symfony\Component\Security\Core\Encoder\EncoderFactory;

class CityModel extends ObjectManager
{
	/**
	 * @var EncoderFactory
	 */
	private $encoderFactory;

	public function __construct(ContainerInterface $containerInterface)
	{
		parent::__construct($containerInterface);
		$this->encoderFactory = $this->container->get('security.encoder_factory');
	}

	/**
	 * @return EntityRepository
	 */
	function getRepository()
	{
		return $this->em->getRepository('AppBundle:City');
	}
}