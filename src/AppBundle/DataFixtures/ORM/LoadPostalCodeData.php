<?php


namespace AppBundle\DataFixtures\ORM;


use AppBundle\Entity\PostalCode;
use Doctrine\Common\DataFixtures\AbstractFixture;
use Doctrine\Common\DataFixtures\OrderedFixtureInterface;
use Doctrine\Common\Persistence\ObjectManager;
use Symfony\Component\Config\Definition\Exception\Exception;
use Symfony\Component\DependencyInjection\ContainerAwareInterface;
use Symfony\Component\DependencyInjection\ContainerInterface;
use Symfony\Component\Yaml\Exception\ParseException;
use Symfony\Component\Yaml\Yaml;

class LoadPostalCodeData extends AbstractFixture implements OrderedFixtureInterface, ContainerAwareInterface
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
		$_file = '/Volumes/G-DRIVE mobile USB/Sites-htdocs/clubEstetica/src/AppBundle/DataFixtures/ORM/Postalcode.csv';
		if (!is_file($_file)) {
			throw new Exception("No se puede leer el fichero");
		}

		$postalCodeCollection = array();
		$postalCode = array();
		if (($file = fopen($_file, "r")) !== FALSE) {

			$nameFields = fgetcsv($file, 0, ";", "\"", "\"");
			$numFields = count($nameFields);

			while (($data = fgetcsv($file, 10000, ";")) !== false) {
				for ($i = 0; $i < $numFields; $i++) {
					$postalCode[$nameFields[$i]] = $data[$i];
				}
				$postalCodeCollection[] = $postalCode;
			}
			fclose($file);
		}

		foreach ($postalCodeCollection as $item) {
			$entity = new PostalCode();
			$entity->setCp($item["cp"]);
			$entity->setCountry($item["country"]);
			$entity->setPlace($item["place"]);
			$entity->setState($item["state"]);
			$entity->setCity($item["city"]);
			$entity->setCityIso($item["city_iso"]);
			$entity->setLatitude($item["latitude"]);
			$entity->setLongitude($item["longitude"]);
			$manager->persist($entity);
		}

		$manager->flush();
	}

	/**
	 * Get the order of this fixture
	 *
	 * @return integer
	 */
	public function getOrder()
	{
		return 1;
	}
}