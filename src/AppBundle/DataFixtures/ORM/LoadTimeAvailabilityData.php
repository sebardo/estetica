<?php


namespace AppBundle\DataFixtures\ORM;

use AppBundle\Entity\Registration\TimeAvailability;
use Doctrine\Common\DataFixtures\AbstractFixture;
use Doctrine\Common\DataFixtures\OrderedFixtureInterface;
use Doctrine\Common\Persistence\ObjectManager;
use Symfony\Component\DependencyInjection\ContainerAwareInterface;
use Symfony\Component\DependencyInjection\ContainerInterface;

class LoadTimeAvailabilityData extends AbstractFixture implements OrderedFixtureInterface, ContainerAwareInterface
{
	/**
	 * @var ContainerInterface
	 */
	private $container;

	/**
	 * Sets the container.
	 *
	 * @param ContainerInterface|null $container A ContainerInterface instance or null
	 */
	public function setContainer(ContainerInterface $container = null)
	{
		$this->container = $container;
	}

	/**
	 * Load data fixtures with the passed EntityManager
	 *
	 * @param ObjectManager $manager
	 */
	public function load(ObjectManager $manager)
	{
		// Complete
		$entity = new TimeAvailability();
		$entity->setName('Completo');
		$manager->persist($entity);
		// Partial
		$entity = new TimeAvailability();
		$entity->setName('Parcial');
		$manager->persist($entity);
		// ByHours
		$entity = new TimeAvailability();
		$entity->setName('Por horas');
		$manager->persist($entity);

		$manager->flush();
	}

	/**
	 * Get the order of this fixture
	 *
	 * @return integer
	 */
	public function getOrder()
	{
		return 80;
	}
}