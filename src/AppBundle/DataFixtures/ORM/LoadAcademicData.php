<?php


namespace AppBundle\DataFixtures\ORM;

use AppBundle\Entity\AcademicStudy;
use Doctrine\Common\DataFixtures\AbstractFixture;
use Doctrine\Common\DataFixtures\OrderedFixtureInterface;
use Doctrine\Common\Persistence\ObjectManager;
use Symfony\Component\DependencyInjection\ContainerAwareInterface;
use Symfony\Component\DependencyInjection\ContainerInterface;

class LoadAcademicData extends AbstractFixture implements OrderedFixtureInterface, ContainerAwareInterface
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
		// LPG
		$entity = new AcademicStudy();
		$entity->setName('LPG');
		$manager->persist($entity);
		// INDIBA
		$entity = new AcademicStudy();
		$entity->setName('INDIBA');
		$manager->persist($entity);
		// SYNERON CANDELA
		$entity = new AcademicStudy();
		$entity->setName('SYNERON CANDELA');
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
		return 130;
	}
}