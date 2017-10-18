<?php


namespace AppBundle\DataFixtures\ORM;

use AppBundle\Entity\Registration\Speciality;
use AppBundle\Entity\Registration\ParentSpeciality;
use Doctrine\Common\DataFixtures\AbstractFixture;
use Doctrine\Common\DataFixtures\OrderedFixtureInterface;
use Doctrine\Common\Persistence\ObjectManager;
use Symfony\Component\DependencyInjection\ContainerAwareInterface;
use Symfony\Component\DependencyInjection\ContainerInterface;

class LoadSpecialityData extends AbstractFixture implements OrderedFixtureInterface, ContainerAwareInterface
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
		$esteticaCollection = array();
		$mediciaEsteticaCollection = array();

		// Children Estética
		$entity = new Speciality();
		$entity->setName('Manicura');
		$manager->persist($entity);
		$esteticaCollection[] = $entity;
		//----
		$entity = new Speciality();
		$entity->setName('Pedicura');
		$manager->persist($entity);
		$esteticaCollection[] = $entity;
		//----
		$entity = new Speciality();
		$entity->setName('Maquillaje');
		$manager->persist($entity);
		$esteticaCollection[] = $entity;
		//----
		$entity = new Speciality();
		$entity->setName('Peluquería');
		$manager->persist($entity);
		$esteticaCollection[] = $entity;
		//----
		$entity = new Speciality();
		$entity->setName('Tratamientos Faciales');
		$manager->persist($entity);
		$esteticaCollection[] = $entity;
		//----
		$entity = new Speciality();
		$entity->setName('Tratamientos Corporales');
		$manager->persist($entity);
		$esteticaCollection[] = $entity;
		//----
		$entity = new Speciality();
		$entity->setName('Depilación (Cera)');
		$manager->persist($entity);
		$esteticaCollection[] = $entity;
		//----
		$entity = new Speciality();
		$entity->setName('Fotodepilación (IPL)');
		$manager->persist($entity);
		$esteticaCollection[] = $entity;
		//----
		$entity = new Speciality();
		$entity->setName('Masajes');
		$manager->persist($entity);
		$esteticaCollection[] = $entity;
		//----
		$entity = new Speciality();
		$entity->setName('Aparatología Básica (RF, Cavi, IPL)');
		$manager->persist($entity);
		$esteticaCollection[] = $entity;
		//----
		$entity = new Speciality();
		$entity->setName('Otros Estética');
		$manager->persist($entity);
		$esteticaCollection[] = $entity;

		// Children Medicina Estética
		//----
		$entity = new Speciality();
		$entity->setName('HIFU');
		$manager->persist($entity);
		$mediciaEsteticaCollection[] = $entity;
		//----
		$entity = new Speciality();
		$entity->setName('Criolipolisis');
		$manager->persist($entity);
		$mediciaEsteticaCollection[] = $entity;
		//----
		$entity = new Speciality();
		$entity->setName('Láser Diodo');
		$manager->persist($entity);
		$mediciaEsteticaCollection[] = $entity;
		//----
		$entity = new Speciality();
		$entity->setName('Diatermia');
		$manager->persist($entity);
		$mediciaEsteticaCollection[] = $entity;
		//----
		$entity = new Speciality();
		$entity->setName('Peelings Químicos');
		$manager->persist($entity);
		$mediciaEsteticaCollection[] = $entity;
		//----
		$entity = new Speciality();
		$entity->setName('Hilos Reabsorbibles');
		$manager->persist($entity);
		$mediciaEsteticaCollection[] = $entity;
		//----
		$entity = new Speciality();
		$entity->setName('Dietética y Nutrición');
		$manager->persist($entity);
		$mediciaEsteticaCollection[] = $entity;
		//----
		$entity = new Speciality();
		$entity->setName('Micropigmentación');
		$manager->persist($entity);
		$mediciaEsteticaCollection[] = $entity;
		//----
		$entity = new Speciality();
		$entity->setName('Otros Medicina Estética');
		$manager->persist($entity);
		$mediciaEsteticaCollection[] = $entity;

		// Estética
		$parentSpeciality = new ParentSpeciality();
		$parentSpeciality->setName('Estética');
		foreach ($esteticaCollection as $speciality) {
			if($speciality instanceof Speciality) {
				$parentSpeciality->addSpeciality($speciality);
				$speciality->setParent($parentSpeciality);
				$manager->persist($speciality);
			}
		}
		$manager->persist($parentSpeciality);

		// Medicina Estética
		$parentSpeciality = new ParentSpeciality();
		$parentSpeciality->setName('Medicina Estética');
		foreach ($mediciaEsteticaCollection as $speciality) {
			if($speciality instanceof Speciality) {
				$parentSpeciality->addSpeciality($speciality);
				$speciality->setParent($parentSpeciality);
				$manager->persist($speciality);
			}
		}
		$manager->persist($parentSpeciality);

		$manager->flush();
	}

	/**
	 * Get the order of this fixture
	 *
	 * @return integer
	 */
	public function getOrder()
	{
		return 50;
	}
}