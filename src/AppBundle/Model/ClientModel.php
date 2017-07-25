<?php


namespace AppBundle\Model;


use AppBundle\Entity\User;
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
	 * @param User $user
	 */
	public function encodePassword(User $user)
	{
		$encoder = $this->container->get('security.encoder_factory')->getEncoder($user);
		$passwordCoded = $encoder->encodePassword(
			$user->getPassword(),
			$user->getSalt()
		);
		$user->setPassword($passwordCoded);
	}

	/**
	 * @param User $user
	 * @param      $plaintextPassword
	 *
	 * @return bool
	 */
	public function checkPassword(User $user, $plaintextPassword)
	{
		$encoder = $this->encoderFactory->getEncoder($user);

		return $encoder->isPasswordValid($user->getPassword(), $plaintextPassword, $user->getSalt());
	}

	/**
	 * @return EntityRepository
	 */
	function getRepository()
	{
		return $this->em->getRepository('AppBundle:Client');
	}
}