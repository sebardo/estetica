<?php


namespace AppBundle\DataFixtures\ORM;


use AppBundle\Entity\City;
use AppBundle\Entity\Country;
use AppBundle\Entity\PostalCode;
use AppBundle\Entity\Province;
use AppBundle\Services\Slugify;
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
		$_file = $this->container->getParameter('postal_code_csv.path');
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

		$countryCollection = array();
		$provinceCollection = array();
		$cityCollection = array();
		foreach ($postalCodeCollection as $item) {
			//Country
			$countrySlug = Slugify::slug($item["country"]);
			if(!in_array($countrySlug, $countryCollection)){
				$countryCollection[] = $countrySlug;
				$country = new Country();
				$country->setName($item["country"]);
				$country->setSlug($countrySlug);
				$manager->persist($country);
			}else{
				$country = $this->container->get('webapp.manager.country_manager')->getOneBy(array('slug' => $countrySlug));
			}
			//Province
			$provinceSlug = Slugify::slug($item["city"]);
			if(!in_array($provinceSlug, $provinceCollection)){
				$provinceCollection[] = $provinceSlug;
				$province = new Province();
				$province->setName($item["city"]);
				$province->setSlug($provinceSlug);
				$province->setCountry($country);
				$manager->persist($province);
			}else{
				$province = $this->container->get('webapp.manager.province_manager')->getOneBy(array('slug' => $provinceSlug));
			}
			//City
			$citySlug = Slugify::slug($item["place"]);
			if(!in_array($citySlug, $cityCollection)){
				$cityCollection[] = $citySlug;
				$city = new City();
				$city->setName($item["place"]);
				$city->setSlug($citySlug);
				$city->setProvince($province);
				$manager->persist($city);
			}else{
				$city = $this->container->get('webapp.manager.city_manager')->getOneBy(array('slug' => $citySlug));
			}
			$manager->flush();
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