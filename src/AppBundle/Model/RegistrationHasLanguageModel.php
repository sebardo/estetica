<?php


namespace AppBundle\Model;


use Doctrine\ORM\EntityRepository;
use Symfony\Component\DependencyInjection\ContainerInterface;

class RegistrationHasLanguageModel extends ObjectManager
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
		return $this->em->getRepository('AppBundle:RegistrationHasLanguage');
	}
}