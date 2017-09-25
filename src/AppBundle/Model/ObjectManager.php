<?php


namespace AppBundle\Model;

use AppBundle\Entity\Timestampable;
use Doctrine\ORM\EntityManager;
use Doctrine\ORM\EntityRepository;
use Symfony\Component\DependencyInjection\ContainerInterface;

abstract class ObjectManager
{
	/**
	 * @var ContainerInterface $container
	 */
	protected $container;

	/**
	 * @var EntityManager $em
	 */
	protected $em;

	public function __construct(ContainerInterface $containerInterface)
	{
		$this->container = $containerInterface;
		$this->em = $containerInterface->get('doctrine')->getManager();
	}

	/**
	 * @param Timestampable $entity
	 * @param boolean $flush
	 */
	public function save(Timestampable $entity, $flush = true)
	{
		$this->em->persist($entity);

		if($flush){
			$this->em->flush();
		}
	}

	/**
	 * @param $dataCollection
	 * @param boolean $flush
	 */
	public function saveArray($dataCollection, $flush = true)
	{
		foreach($dataCollection as $entity){
			$this->em->persist($entity);
		}

		if($flush){
			$this->em->flush();
		}
	}

	/**
	 * @param Timestampable $entity
	 * @param boolean $flush
	 */
	public function remove(Timestampable $entity, $flush = true)
	{
		$this->em->remove($entity);

		if($flush){
			$this->em->flush();
		}
	}

	public function getOneBy($fields = array(), $order = array())
	{
		return $this->getRepository()->findOneBy($fields, $order);
	}

	public function getBy($fields = array(), $order = array())
	{
		return $this->getRepository()->findBy($fields, $order);
	}

	/**
	 * @return EntityRepository
	 */
	abstract function getRepository();
}