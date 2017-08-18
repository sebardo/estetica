<?php


namespace AppBundle\DataFixtures\ORM;

use AppBundle\Entity\Registration\Course;
use Doctrine\Common\DataFixtures\AbstractFixture;
use Doctrine\Common\DataFixtures\OrderedFixtureInterface;
use Doctrine\Common\Persistence\ObjectManager;
use Symfony\Component\DependencyInjection\ContainerAwareInterface;
use Symfony\Component\DependencyInjection\ContainerInterface;

class LoadCourseData extends AbstractFixture implements OrderedFixtureInterface, ContainerAwareInterface
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
		// Maquillaje
		$entity = new Course();
		$entity->setName('Maquillaje');
		$manager->persist($entity);
		// Manicura
		$entity = new Course();
		$entity->setName('Manicura');
		$manager->persist($entity);
		// Balneoterapia
		$entity = new Course();
		$entity->setName('Balneoterapia');
		$manager->persist($entity);
		// Cosmetica
		$entity = new Course();
		$entity->setName('Cosmetica');
		$manager->persist($entity);
		// Nutrición
		$entity = new Course();
		$entity->setName('Nutrición');
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
		return 120;
	}
}