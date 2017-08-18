<?php


namespace AppBundle\DataFixtures\ORM;

use AppBundle\Entity\Registration\Experience;
use Doctrine\Common\DataFixtures\AbstractFixture;
use Doctrine\Common\DataFixtures\OrderedFixtureInterface;
use Doctrine\Common\Persistence\ObjectManager;
use Symfony\Component\DependencyInjection\ContainerAwareInterface;
use Symfony\Component\DependencyInjection\ContainerInterface;

class LoadExperienceData extends AbstractFixture implements OrderedFixtureInterface, ContainerAwareInterface
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
		// Sin Experiencia
		$entity = new Experience();
		$entity->setName('Sin Experiencia');
		$manager->persist($entity);
		// 1 year
		$entity = new Experience();
		$entity->setName('1 año');
		$manager->persist($entity);
		// 2 years
		$entity = new Experience();
		$entity->setName('2 años');
		$manager->persist($entity);
		// 3 years
		$entity = new Experience();
		$entity->setName('3 años');
		$manager->persist($entity);
		// 4 years
		$entity = new Experience();
		$entity->setName('4 años');
		$manager->persist($entity);
		// 5 years
		$entity = new Experience();
		$entity->setName('5 años');
		$manager->persist($entity);
		// 6 years
		$entity = new Experience();
		$entity->setName('6 años');
		$manager->persist($entity);
		// 7 years
		$entity = new Experience();
		$entity->setName('7 años');
		$manager->persist($entity);
		// 8 years
		$entity = new Experience();
		$entity->setName('8 años');
		$manager->persist($entity);
		// 9 years
		$entity = new Experience();
		$entity->setName('9 años');
		$manager->persist($entity);
		// 10 years
		$entity = new Experience();
		$entity->setName('10 años');
		$manager->persist($entity);
		// 11 years
		$entity = new Experience();
		$entity->setName('11 años');
		$manager->persist($entity);
		// 12 years
		$entity = new Experience();
		$entity->setName('12 años');
		$manager->persist($entity);
		// 13 years
		$entity = new Experience();
		$entity->setName('13 años');
		$manager->persist($entity);
		// 14 years
		$entity = new Experience();
		$entity->setName('14 años');
		$manager->persist($entity);
		// 15 year
		$entity = new Experience();
		$entity->setName('15 años');
		$manager->persist($entity);
		// more than 15 years
		$entity = new Experience();
		$entity->setName('+ de 15 años');
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
		return 60;
	}
}