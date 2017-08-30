<?php


namespace AppBundle\Model;


use AppBundle\Entity\Registration;
use Doctrine\ORM\EntityRepository;
use Symfony\Component\DependencyInjection\ContainerInterface;

class RegistrationModel extends ObjectManager
{
	public function __construct(ContainerInterface $containerInterface)
	{
	    parent::__construct($containerInterface);
	}

	/**
	 * @return EntityRepository
	 */
	function getRepository()
	{
		return $this->em->getRepository('AppBundle:Registration');
	}

	public function getQueryByFilterForm($data)
	{
		if (!empty($data)) {
			return $this->getRepository()->findQueryByFilterForm($data);
		}

		return $this->getBy(array());
	}

	public function create(Registration $entity)
	{
		$this->save($entity);

		return $entity;
	}
}