<?php


namespace AppBundle\Model;


use AppBundle\Entity\Registration;
use Doctrine\ORM\EntityRepository;
use Symfony\Component\DependencyInjection\ContainerInterface;

class RegistrationModel extends ObjectManager
{
	public $registrationUploadDirectory;

	public function __construct(ContainerInterface $containerInterface, $registrationUploadDirectory)
	{
		$this->registrationUploadDirectory = $registrationUploadDirectory;
	    parent::__construct($containerInterface);
	}

	/**
	 * @return \AppBundle\Repository\RegistrationRepository
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
		$this->uploadPhoto($entity);
		$this->uploadCV($entity);
		$this->save($entity);

		return $entity;
	}

	public function uploadPhoto(Registration $entity)
	{
		$entity->uploadPhoto($this->registrationUploadDirectory);
	}

	public function uploadCV(Registration $entity)
	{
		$entity->uploadCV($this->registrationUploadDirectory);
	}
}