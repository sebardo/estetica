<?php


namespace AppBundle\DataFixtures\ORM;


use AppBundle\Entity\Plan;
use Doctrine\Common\DataFixtures\AbstractFixture;
use Doctrine\Common\DataFixtures\OrderedFixtureInterface;
use Doctrine\Common\Persistence\ObjectManager;
use Symfony\Component\DependencyInjection\ContainerAwareInterface;
use Symfony\Component\DependencyInjection\ContainerInterface;

class LoadPlansData extends AbstractFixture implements OrderedFixtureInterface, ContainerAwareInterface
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
		//Mini
		$entity = new Plan();
		$entity->setName('Mini');
		$entity->setDescription('');
		$entity->setMonthlyPrize(8);
		$entity->setOtherPrize('8€/mes');
		$manager->persist($entity);

		//SMALL
		$entity = new Plan();
		$entity->setName('Small');
		$entity->setDescription('');
		$entity->setMonthlyPrize(17);
		$entity->setOtherPrize('17€/mes');
		$manager->persist($entity);

		//STAR
		$entity = new Plan();
		$entity->setName('Star');
		$entity->setDescription('');
		$entity->setMonthlyPrize(49);
		$entity->setOtherPrize('49€/mes');
		$manager->persist($entity);

		//PREMIUM
		$entity = new Plan();
		$entity->setName('Premium');
		$entity->setDescription('');
		$entity->setMonthlyPrize(129);
		$entity->setOtherPrize('129€/mes');
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
		return 10;
	}

}