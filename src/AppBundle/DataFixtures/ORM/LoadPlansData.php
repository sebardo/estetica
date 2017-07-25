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
		$entity->setDescription('Lorem ipsum dolor sit amet, consectetur adipiscing elit. Ut ornare in orci vel condimentum. Sed ac tellus quis justo rutrum consectetur elementum elementum ex. Sed tincidunt imperdiet lacus laoreet consectetur. Vestibulum finibus consectetur lobortis. Quisque porttitor id nunc et porttitor. Vestibulum rhoncus quam in libero euismod, feugiat efficitur libero placerat. Cras pharetra, nulla sed commodo auctor, enim eros blandit quam, nec condimentum lorem dui id est. Integer vitae volutpat erat, et bibendum massa. Sed convallis ac nunc at mattis.');
		$entity->setMonthlyPrize(17);
		$entity->setOtherPrize('204€/pago anual');
		$manager->persist($entity);

		//Basic
		$entity = new Plan();
		$entity->setName('Basic');
		$entity->setDescription('Lorem ipsum dolor sit amet, consectetur adipiscing elit. Ut ornare in orci vel condimentum. Sed ac tellus quis justo rutrum consectetur elementum elementum ex. Sed tincidunt imperdiet lacus laoreet consectetur. Vestibulum finibus consectetur lobortis. Quisque porttitor id nunc et porttitor. Vestibulum rhoncus quam in libero euismod, feugiat efficitur libero placerat. Cras pharetra, nulla sed commodo auctor, enim eros blandit quam, nec condimentum lorem dui id est. Integer vitae volutpat erat, et bibendum massa. Sed convallis ac nunc at mattis.');
		$entity->setMonthlyPrize(49);
		$entity->setOtherPrize('294€/pago semestral');
		$manager->persist($entity);

		//Star
		$entity = new Plan();
		$entity->setName('Star');
		$entity->setDescription('Lorem ipsum dolor sit amet, consectetur adipiscing elit. Ut ornare in orci vel condimentum. Sed ac tellus quis justo rutrum consectetur elementum elementum ex. Sed tincidunt imperdiet lacus laoreet consectetur. Vestibulum finibus consectetur lobortis. Quisque porttitor id nunc et porttitor. Vestibulum rhoncus quam in libero euismod, feugiat efficitur libero placerat. Cras pharetra, nulla sed commodo auctor, enim eros blandit quam, nec condimentum lorem dui id est. Integer vitae volutpat erat, et bibendum massa. Sed convallis ac nunc at mattis.');
		$entity->setMonthlyPrize(129);
		$entity->setOtherPrize('387€/pago trimestral');
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