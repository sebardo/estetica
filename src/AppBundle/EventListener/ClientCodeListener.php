<?php


namespace AppBundle\EventListener;

use AppBundle\Services\ClientCodeGenerator;
use Doctrine\ORM\Event\LifecycleEventArgs;
use AppBundle\Entity\Client;

class ClientCodeListener
{
	public function postPersist(LifecycleEventArgs $args)
	{
		$object = $args->getObject();

		if(!$object instanceof Client) {
			return;
		}

		$em = $args->getEntityManager();

		$object->setCode(ClientCodeGenerator::createNextCode($object->getId()));
		$em->persist($object);
		$em->flush();
	}
}