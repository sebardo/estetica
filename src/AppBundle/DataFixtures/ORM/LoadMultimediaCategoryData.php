<?php


namespace AppBundle\DataFixtures\ORM;

use AppBundle\Entity\MultimediaCategory;
use Doctrine\Common\DataFixtures\AbstractFixture;
use Doctrine\Common\DataFixtures\OrderedFixtureInterface;
use Doctrine\Common\Persistence\ObjectManager;
use Symfony\Component\DependencyInjection\ContainerAwareInterface;
use Symfony\Component\DependencyInjection\ContainerInterface;

class LoadMultimediaCategoryData extends AbstractFixture implements OrderedFixtureInterface, ContainerAwareInterface
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
		// 1
		$entity1 = new MultimediaCategory();
		$entity1->setName('radiofrecuencia');
		$manager->persist($entity1);
		// 2
		$entity2 = new MultimediaCategory();
		$entity2->setName('criopolis');
		$manager->persist($entity2);
		// 3
		$entity3 = new MultimediaCategory();
		$entity3->setName('hifu');
		$manager->persist($entity3);
		// 4
		$entity4 = new MultimediaCategory();
		$entity4->setName('vacuum');
		$manager->persist($entity4);
		// 5
		$entity5 = new MultimediaCategory();
		$entity5->setName('cavitación');
		$manager->persist($entity5);
		// 6
		$entity6 = new MultimediaCategory();
		$entity6->setName('ipl');
		$manager->persist($entity6);
		// 7
		$entity7 = new MultimediaCategory();
		$entity7->setName('diodo');
		$manager->persist($entity7);
		// 8
		$entity8 = new MultimediaCategory();
		$entity8->setName('presoterapia');
		$manager->persist($entity8);
		// 9
		$entity9 = new MultimediaCategory();
		$entity9->setName('electro-estimulación');
		$manager->persist($entity9);
		// 10
		$entity10 = new MultimediaCategory();
		$entity10->setName('onda de choque');
		$manager->persist($entity10);
		// 11
		$entity11 = new MultimediaCategory();
		$entity11->setName('láser lipolítico');
		$manager->persist($entity11);
		// 12
		$entity12 = new MultimediaCategory();
		$entity12->setName('narl');
		$manager->persist($entity12);


		$manager->flush();
	}

	/**
	 * Get the order of this fixture
	 *
	 * @return integer
	 */
	public function getOrder()
	{
		return 30;
	}

}