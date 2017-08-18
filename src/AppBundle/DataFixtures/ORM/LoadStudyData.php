<?php


namespace AppBundle\DataFixtures\ORM;

use AppBundle\Entity\Registration\Study;
use Doctrine\Common\DataFixtures\AbstractFixture;
use Doctrine\Common\DataFixtures\OrderedFixtureInterface;
use Doctrine\Common\Persistence\ObjectManager;
use Symfony\Component\DependencyInjection\ContainerAwareInterface;
use Symfony\Component\DependencyInjection\ContainerInterface;

class LoadStudyData extends AbstractFixture implements OrderedFixtureInterface, ContainerAwareInterface
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
		$entity = new Study();
		$entity->setName('Grado Medio - General');
		$entity->setHighLevel(false);
		$manager->persist($entity);
		// High - Fisioterapia
		$entity = new Study();
		$entity->setName('Grado Superior - Fisioterapia');
		$entity->setHighLevel(true);
		$manager->persist($entity);
		// High - Enfermería
		$entity = new Study();
		$entity->setName('Grado Superior - Enfermería');
		$entity->setHighLevel(true);
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
		return 110;
	}
}