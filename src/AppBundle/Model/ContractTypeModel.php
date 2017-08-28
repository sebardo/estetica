<?php


namespace AppBundle\Model;


use Doctrine\ORM\EntityRepository;
use Symfony\Component\DependencyInjection\ContainerInterface;

class ContractTypeModel extends ObjectManager
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
		return $this->em->getRepository('AppBundle:Registration\ContractType');
	}
}