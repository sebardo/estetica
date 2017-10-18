<?php


namespace AppBundle\DataFixtures\ORM;

use AppBundle\Entity\Registration\Language;
use Doctrine\Common\DataFixtures\AbstractFixture;
use Doctrine\Common\DataFixtures\OrderedFixtureInterface;
use Doctrine\Common\Persistence\ObjectManager;
use Symfony\Component\DependencyInjection\ContainerAwareInterface;
use Symfony\Component\DependencyInjection\ContainerInterface;

class LoadLanguageData extends AbstractFixture implements OrderedFixtureInterface, ContainerAwareInterface
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
		// Spanish
		$entity = new Language();
		$entity->setName('Español');
		$manager->persist($entity);
		// English
		$entity = new Language();
		$entity->setName('Inglés');
		$manager->persist($entity);
		// Catalan
		$entity = new Language();
		$entity->setName('Catalán');
		$manager->persist($entity);
		// French
		$entity = new Language();
		$entity->setName('Francés');
		$manager->persist($entity);
		// Italian
		$entity = new Language();
		$entity->setName('Italiano');
		$manager->persist($entity);
		// German
		$entity = new Language();
		$entity->setName('Alemán');
		$manager->persist($entity);
		// Russian
		$entity = new Language();
		$entity->setName('Ruso');
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
		return 70;
	}
}