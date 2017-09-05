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
use Symfony\Component\Finder\Finder;
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

		$this->createPostalCode($manager, $_file, true);
	}

	private function createPostalCode(ObjectManager $manager, $_file, $flush = false)
	{
		$postalCodeCollection = array();
		$postalCode = array();
		if (($file = fopen($_file, "r")) !== FALSE) {

			$nameFields = fgetcsv($file, 0, ";", "\"", "\"");
			$numFields = count($nameFields);

			while (($data = fgetcsv($file, 10000, ";")) !== false) {
				for ($i = 0; $i < $numFields; $i++) {
					$postalCode[$i] = $data[$i];
				}
				$postalCodeCollection[] = $postalCode;
			}
			fclose($file);
		}

		$countryCollection = array();
		$_countryCollection = $this->container->get('webapp.manager.country_manager')->getBy(array());
		foreach ($_countryCollection as $item) {
			$countryCollection[] = $item->getSlug();
		}

		$provinceCollection = array();
		$_provinceCollection = $this->container->get('webapp.manager.province_manager')->getBy(array());
		foreach ($_provinceCollection as $item) {
			$provinceCollection[] = $item->getSlug();
		}

		$cityCollection = array();
		$_cityCollection = $this->container->get('webapp.manager.city_manager')->getBy(array());
		foreach ($_cityCollection as $item) {
			$cityCollection[] = $item->getSlug();
		}

		$count = 0;
		foreach ($postalCodeCollection as $item) {
			echo "=============================================================\n";
			echo "=============================================================\n";
			echo "=============================================================\n";
			echo "Registro número:  $count\n";
			echo "=============================================================\n";
			//Country
			if(array_key_exists('country', $item)){
				$countryColumn = $item["country"];
				$countrySlug = Slugify::slug($countryColumn);
			} else {
				$countryColumn = $item[0];
				$countrySlug = Slugify::slug($countryColumn);
			}

			if(!in_array($countrySlug, $countryCollection)){
				echo "Escribiendo country:  $countrySlug\n";
				$countryCollection[] = $countrySlug;
				$country = new Country();
				$country->setName($countryColumn);
				$country->setSlug($countrySlug);
				$manager->persist($country);
				$manager->flush();
			}else{
				$country = $this->container->get('webapp.manager.country_manager')->getOneBy(array('slug' => $countrySlug));
				$countrySlug = $country->getSlug();
				echo "Existe country: $countrySlug\n";
			}
			//Province
			if(array_key_exists('city', $item)){
				$provinceColumn = $item["city"];
				$provinceSlug = Slugify::slug($provinceColumn);
			} else {
				$provinceColumn = $item[1];
				$provinceSlug = Slugify::slug($provinceColumn);
			}

			if(!in_array($provinceSlug, $provinceCollection)){
				echo "Escribiendo province:  $provinceSlug\n";
				$provinceCollection[] = $provinceSlug;
				$province = new Province();
				$province->setName($provinceColumn);
				$province->setSlug($provinceSlug);
				$province->setCountry($country);
				$manager->persist($province);
				$manager->flush();
			}else{
				$province = $this->container->get('webapp.manager.province_manager')->getOneBy(array('slug' => $provinceSlug));
				$provinceSlug = $province->getSlug();
				echo "Existe province:  $provinceSlug\n";
			}
			//City
			if(array_key_exists('place', $item)){
				$cityColumn = $item["place"];
				$citySlug = Slugify::slug($cityColumn);
			} else {
				$cityColumn = $item[2];
				$citySlug = Slugify::slug($cityColumn);
			}

//			echo "Escribiendo city:  $citySlug\n";
//			$cityCollection[] = $citySlug;
//			$city = new City();
//			$city->setName($cityColumn);
//			$city->setSlug($citySlug);
//			$city->setProvince($province);
//			$manager->persist($city);
//			$manager->flush();

			if(!in_array($citySlug, $cityCollection)){
				echo "Escribiendo city:  $citySlug\n";
				$cityCollection[] = $citySlug;
				$city = new City();
				$city->setName($cityColumn);
				$city->setSlug($citySlug);
				$city->setProvince($province);
				$manager->persist($city);
				$manager->flush();
			}else{
				$city = $this->container->get('webapp.manager.city_manager')->getOneBy(array('slug' => $citySlug));
				$citySlug = $city->getSlug();
				echo "Existe city:  $citySlug\n";
			}
			$count++;
			//$manager->flush();
		}

		if($flush){
			$manager->flush();
		}
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