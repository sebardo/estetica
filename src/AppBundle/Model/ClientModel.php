<?php


namespace AppBundle\Model;


use AppBundle\Entity\Client;
use Doctrine\ORM\EntityRepository;
use Symfony\Component\DependencyInjection\ContainerInterface;
use Symfony\Component\Security\Core\Encoder\EncoderFactory;

class ClientModel extends ObjectManager
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
	 * @param Client $client
	 */
	public function encodePassword(Client $client)
	{
		$encoder = $this->container->get('security.encoder_factory')->getEncoder($client);
		$passwordCoded = $encoder->encodePassword(
			$client->getPassword(),
			$client->getSalt()
		);
		$client->setPassword($passwordCoded);
	}

	/**
	 * @param Client $client
	 * @param      $plaintextPassword
	 *
	 * @return bool
	 */
	public function checkPassword(Client $client, $plaintextPassword)
	{
		$encoder = $this->encoderFactory->getEncoder($client);

		return $encoder->isPasswordValid($client->getPassword(), $plaintextPassword, $client->getSalt());
	}

	/**
	 * @return EntityRepository
	 */
	function getRepository()
	{
		return $this->em->getRepository('AppBundle:Client');
	}
}