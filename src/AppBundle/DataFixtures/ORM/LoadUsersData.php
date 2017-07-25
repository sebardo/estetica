<?php

namespace AppBundle\DataFixtures\ORM;

use AppBundle\Entity\Address;
use Doctrine\Common\DataFixtures\AbstractFixture;
use AppBundle\Entity\User;
use AppBundle\Entity\Client;
use Doctrine\Common\DataFixtures\OrderedFixtureInterface;
use Doctrine\Common\Persistence\ObjectManager;
use Symfony\Component\DependencyInjection\ContainerAwareInterface;
use Symfony\Component\DependencyInjection\ContainerInterface;

class LoadUsersData extends AbstractFixture implements OrderedFixtureInterface, ContainerAwareInterface
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
		$entity = new Client();
		$entity->setUsername('admin_huser');
		$entity->setPassword('Admin123');
		$this->container->get('webapp.manager.client_manager')->encodePassword($entity);
		$entity->addRole(User::ROLE_ADMIN);
		$entity->setShortDescription("");
		$entity->setDescription("");
		$entity->setNif("");
		$entity->setSocialNumber("");
		$entity->setSocietyName("");
		$entity->setTagLine("");
		$entity->setTechnology("");
		$entity->setTradeName("");

		$manager->persist($entity);

		$clientsFileName = 'clients_created_'.time().'.txt';
		$fileUsernameAndPasswords = fopen($clientsFileName, 'w');
		for($i = 0; $i < 10; $i++){
			//User Entity
			$entity = new Client();
			$entity->setShortDescription('Lorem ipsum dolor sit amet, consectetur adipiscing elit. Ut ornare in orci vel condimentum. Sed ac tellus quis justo rutrum consectetur elementum elementum ex. Sed tincidunt imperdiet lacus laoreet consectetur. Vestibulum finibus consectetur lobortis. Quisque porttitor id nunc et porttitor. Vestibulum rhoncus quam in libero euismod, feugiat efficitur libero placerat. Cras pharetra, nulla sed commodo auctor, enim eros blandit quam, nec condimentum lorem dui id est. Integer vitae volutpat erat, et bibendum massa. Sed convallis ac nunc at mattis.');
			$entity->setDescription('Phasellus ultricies efficitur maximus. Fusce mattis non neque eget consectetur. Vestibulum tincidunt a erat quis ullamcorper. Aliquam enim lorem, dignissim non ullamcorper ac, suscipit vitae erat. Nullam at leo pretium, facilisis enim sit amet, euismod mi. Aenean nec nulla non lacus tempus posuere. Sed et magna a velit sagittis dapibus. Vestibulum et molestie mauris, venenatis rhoncus mauris. Nullam eget luctus neque. Vivamus hendrerit, est quis semper consectetur, ante nisl placerat diam, et rutrum felis eros eget mi. Sed sed magna nunc. Vivamus quam arcu, laoreet vitae sollicitudin ut, dapibus in velit. Praesent sapien nisi, lacinia vitae urna non, imperdiet sagittis tortor. Donec scelerisque, enim vitae porttitor aliquet, sapien est aliquam sem, in ultricies erat erat iaculis risus. Donec congue sagittis tristique. Nam in commodo nunc. Mauris vel vulputate orci, ut laoreet nisi. Vivamus fermentum tristique sagittis.');
			$entity->setNif(md5(time()));
			$entity->setSocialNumber(md5(time()));
			$entity->setSocietyName('societyName_client_example_' . $i);
			$entity->setTagLine('Lorem ipsum dolor sit amet');
			$entity->setTechnology('Sed pharetra commodo efficitur. Aliquam dictum nisl ut sem faucibus ultricies. Curabitur porttitor dui interdum maximus mollis. Curabitur a dignissim enim, vel congue mauris. Etiam vitae ultrices augue. Class aptent taciti sociosqu ad litora torquent per conubia nostra, per inceptos himenaeos. In hac habitasse platea dictumst. Pellentesque quis commodo augue, a suscipit felis. Phasellus non elementum magna. Maecenas consectetur sodales accumsan. Curabitur ultricies faucibus tortor, sed sagittis sapien laoreet quis. Nulla molestie, neque eu placerat malesuada, eros nisi tincidunt nisi, sed pretium neque lorem vel massa. Maecenas dignissim dapibus libero, nec consequat diam blandit quis.');
			$entity->setTradeName('tradeName_client_example_' . $i);
			//Address Facturacion
			$address1 = new Address();
			$address1->setAddress('Calle Nosequé N13 puerta 20');
			$address1->setContact('Nombre de contacto');
			$address1->setPhone(md5(time()));
			$address1->setEmail('client_example_' . $i . '@gmail.com');
			//PostalCode
			$postalCodeCollection = $this->container->get('webapp.manager.postalcode_manager')->getBy(array());
			$postalCodeCollectionSize = (count($postalCodeCollection) - 1);
			$randValue = rand(0, $postalCodeCollectionSize);
			$address1->setPostalCode($postalCodeCollection[$randValue]);
			$manager->persist($address1);

			$value = rand(0,1);
			if($value){
				//Address Other
				$address2 = new Address();
				$address2->setAddress('Otra Calle Nosequé N13 puerta 20');
				$address2->setContact('Otro Nombre de contacto');
				$address2->setPhone(md5(time()));
				$address2->setEmail('client_example_' . $i . '_otro@gmail.com');
				//PostalCode
				$postalCodeCollection = $this->container->get('webapp.manager.postalcode_manager')->getBy(array());
				$postalCodeCollectionSize = (count($postalCodeCollection) - 1);
				$randValue = rand(0, $postalCodeCollectionSize);
				$address2->setPostalCode($postalCodeCollection[$randValue]);
				$manager->persist($address2);
			}

			//Plan
			$planCollection = $this->container->get('webapp.manager.plan_manager')->getBy(array());
			$planCollectionSize = (count($planCollection) - 1);
			$randValue = rand(0, $planCollectionSize);
			$entity->setPlan($planCollection[$randValue]);

			//Password
			$_password = User::generateRandomString(10);
			$entity->setPassword($_password);
			$_username = User::generateRandomString(6);
			$entity->setUsername($_username);
			$this->container->get('webapp.manager.client_manager')->encodePassword($entity);

			//Persist
			$manager->persist($entity);
			fwrite($fileUsernameAndPasswords, "Username: " . $entity->getUsername() ." - Password: " . $_password . PHP_EOL);
		}
		fclose($fileUsernameAndPasswords);

		$manager->flush();
	}

	/**
	 * Get the order of this fixture
	 *
	 * @return integer
	 */
	public function getOrder()
	{
		return 15;
	}
}