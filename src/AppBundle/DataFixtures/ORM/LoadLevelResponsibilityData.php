<?php


namespace AppBundle\DataFixtures\ORM;

use AppBundle\Entity\Registration\LevelResponsibility;
use Doctrine\Common\DataFixtures\AbstractFixture;
use Doctrine\Common\DataFixtures\OrderedFixtureInterface;
use Doctrine\Common\Persistence\ObjectManager;
use Symfony\Component\DependencyInjection\ContainerAwareInterface;
use Symfony\Component\DependencyInjection\ContainerInterface;

class LoadLevelResponsibilityData extends AbstractFixture implements OrderedFixtureInterface, ContainerAwareInterface
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
		// Low
		$entity = new LevelResponsibility();
		$entity->setName('Empleado');
		$manager->persist($entity);
		// Middle
		$entity = new LevelResponsibility();
		$entity->setName('Especialista');
		$manager->persist($entity);
		// High
		$entity = new LevelResponsibility();
		$entity->setName('Supervisor/a');
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
		return 100;
	}
}