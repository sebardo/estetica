<?php

namespace AppBundle\DataFixtures\ORM;

use AppBundle\Entity\Address;
use AppBundle\Entity\FileDoc;
use AppBundle\Entity\LocalAddress;
use Doctrine\Common\DataFixtures\AbstractFixture;
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
		$entity->setUsername('admin');
		$entity->setPassword('admin');
		$this->container->get('webapp.manager.client_manager')->encodePassword($entity);
		$entity->addRole(Client::ROLE_ADMIN);
		$entity->setIsAdministrator(true);
		$entity->setShortDescription("");
		$entity->setDescription("");
		$entity->setNif("");
		$entity->setUrlWeb("");
		$entity->setFacebook("");
		$entity->setInstagram("");
		$entity->setBlog("");
		$entity->setSocialNumber("");
		$entity->setSocietyName("");
		$entity->setTagLine("");
		$entity->setTechnology("");
		$entity->setTradeName("");
		$entity->setLogo("logo1.png");
		$manager->persist($entity);
                
                $clientCollection = array();
                
                //User Entity
                $client = new Client();
                // Username
                $client->setUsername('user');
                $client->setPassword('user');
                $this->container->get('webapp.manager.client_manager')->encodePassword($client);
                $client->setShortDescription('Lorem ipsum dolor sit amet, consectetur adipiscing elit. Ut ornare in orci vel condimentum. Sed ac tellus quis justo rutrum consectetur elementum elementum ex. Sed tincidunt imperdiet lacus laoreet consectetur. Vestibulum finibus consectetur lobortis. Quisque porttitor id nunc et porttitor. Vestibulum rhoncus quam in libero euismod, feugiat efficitur libero placerat. Cras pharetra, nulla sed commodo auctor, enim eros blandit quam, nec condimentum lorem dui id est. Integer vitae volutpat erat, et bibendum massa. Sed convallis ac nunc at mattis.');
                $client->setDescription('Phasellus ultricies efficitur maximus. Fusce mattis non neque eget consectetur. Vestibulum tincidunt a erat quis ullamcorper. Aliquam enim lorem, dignissim non ullamcorper ac, suscipit vitae erat. Nullam at leo pretium, facilisis enim sit amet, euismod mi. Aenean nec nulla non lacus tempus posuere. Sed et magna a velit sagittis dapibus. Vestibulum et molestie mauris, venenatis rhoncus mauris. Nullam eget luctus neque. Vivamus hendrerit, est quis semper consectetur, ante nisl placerat diam, et rutrum felis eros eget mi. Sed sed magna nunc. Vivamus quam arcu, laoreet vitae sollicitudin ut, dapibus in velit. Praesent sapien nisi, lacinia vitae urna non, imperdiet sagittis tortor. Donec scelerisque, enim vitae porttitor aliquet, sapien est aliquam sem, in ultricies erat erat iaculis risus. Donec congue sagittis tristique. Nam in commodo nunc. Mauris vel vulputate orci, ut laoreet nisi. Vivamus fermentum tristique sagittis.');
                $client->setNif(md5(time()));
                $client->setUrlWeb("");
                $client->setFacebook("");
                $client->setInstagram("");
                $client->setBlog("");
                $client->setSocialNumber(md5(time()));
                $client->setSocietyName('societyName_client_example_');
                $client->setTagLine('Lorem ipsum dolor sit amet');
                $client->setTechnology('Sed pharetra commodo efficitur. Aliquam dictum nisl ut sem faucibus ultricies. Curabitur porttitor dui interdum maximus mollis. Curabitur a dignissim enim, vel congue mauris. Etiam vitae ultrices augue. Class aptent taciti sociosqu ad litora torquent per conubia nostra, per inceptos himenaeos. In hac habitasse platea dictumst. Pellentesque quis commodo augue, a suscipit felis. Phasellus non elementum magna. Maecenas consectetur sodales accumsan. Curabitur ultricies faucibus tortor, sed sagittis sapien laoreet quis. Nulla molestie, neque eu placerat malesuada, eros nisi tincidunt nisi, sed pretium neque lorem vel massa. Maecenas dignissim dapibus libero, nec consequat diam blandit quis.');
                $client->setTradeName('tradeName_client_example_');
                $client->setLogo("logo1.png");
                //Persist
                $clientCollection[] = $client;
                $manager->persist($client);
                        
                        

		
		//$clientsFileName = 'clients_created_'.time().'.txt';
		//$fileUsernameAndPasswords = fopen($clientsFileName, 'w');
		for($i = 0; $i < 10; $i++){
			//User Entity
			$client = new Client();
			// Username
			$_username = 'user_example_' . $i;
			$client->setUsername($_username);
			//Password
			$_password = Client::generateRandomString(10);
			$client->setPassword($_password);
			$this->container->get('webapp.manager.client_manager')->encodePassword($client);
			$client->setShortDescription('Lorem ipsum dolor sit amet, consectetur adipiscing elit. Ut ornare in orci vel condimentum. Sed ac tellus quis justo rutrum consectetur elementum elementum ex. Sed tincidunt imperdiet lacus laoreet consectetur. Vestibulum finibus consectetur lobortis. Quisque porttitor id nunc et porttitor. Vestibulum rhoncus quam in libero euismod, feugiat efficitur libero placerat. Cras pharetra, nulla sed commodo auctor, enim eros blandit quam, nec condimentum lorem dui id est. Integer vitae volutpat erat, et bibendum massa. Sed convallis ac nunc at mattis.');
			$client->setDescription('Phasellus ultricies efficitur maximus. Fusce mattis non neque eget consectetur. Vestibulum tincidunt a erat quis ullamcorper. Aliquam enim lorem, dignissim non ullamcorper ac, suscipit vitae erat. Nullam at leo pretium, facilisis enim sit amet, euismod mi. Aenean nec nulla non lacus tempus posuere. Sed et magna a velit sagittis dapibus. Vestibulum et molestie mauris, venenatis rhoncus mauris. Nullam eget luctus neque. Vivamus hendrerit, est quis semper consectetur, ante nisl placerat diam, et rutrum felis eros eget mi. Sed sed magna nunc. Vivamus quam arcu, laoreet vitae sollicitudin ut, dapibus in velit. Praesent sapien nisi, lacinia vitae urna non, imperdiet sagittis tortor. Donec scelerisque, enim vitae porttitor aliquet, sapien est aliquam sem, in ultricies erat erat iaculis risus. Donec congue sagittis tristique. Nam in commodo nunc. Mauris vel vulputate orci, ut laoreet nisi. Vivamus fermentum tristique sagittis.');
			$client->setNif(md5(time()));
			$client->setUrlWeb("");
			$client->setFacebook("");
			$client->setInstagram("");
			$client->setBlog("");
			$client->setSocialNumber(md5(time()));
			$client->setSocietyName('societyName_client_example_' . $i);
			$client->setTagLine('Lorem ipsum dolor sit amet');
			$client->setTechnology('Sed pharetra commodo efficitur. Aliquam dictum nisl ut sem faucibus ultricies. Curabitur porttitor dui interdum maximus mollis. Curabitur a dignissim enim, vel congue mauris. Etiam vitae ultrices augue. Class aptent taciti sociosqu ad litora torquent per conubia nostra, per inceptos himenaeos. In hac habitasse platea dictumst. Pellentesque quis commodo augue, a suscipit felis. Phasellus non elementum magna. Maecenas consectetur sodales accumsan. Curabitur ultricies faucibus tortor, sed sagittis sapien laoreet quis. Nulla molestie, neque eu placerat malesuada, eros nisi tincidunt nisi, sed pretium neque lorem vel massa. Maecenas dignissim dapibus libero, nec consequat diam blandit quis.');
			$client->setTradeName('tradeName_client_example_' . $i);
			$client->setLogo("logo1.png");
			//Persist
			$clientCollection[] = $client;
			$manager->persist($client);
			//fwrite($fileUsernameAndPasswords, "Username: " . $client->getUsername() ." - Password: " . $_password . PHP_EOL);
		}
		fclose($fileUsernameAndPasswords);

		$this->setAddress($clientCollection);
		$this->setFiles($clientCollection);
		$this->setPlan($clientCollection);

		$manager->flush();
	}

	private function setAddress($clientCollection)
	{
		$boolean = true;
		foreach ($clientCollection as $client) {
			if($boolean){
				$this->setAddressByClient($client, $boolean);
				$boolean = false;
			}else{
				$this->setAddressByClient($client, $boolean);
				$boolean = true;
			}
		}
	}

	private function setAddressByClient(Client $client, $boolean)
	{
		$manager = $this->container->get('doctrine.orm.default_entity_manager');
		//Billing Address
		$billingAddress = new Address();
		$billingAddress->setAddress('Calle Nosequé N13 puerta 20');
		$billingAddress->setContact('Nombre de contacto');
		$billingAddress->setPhone(md5(time()));
		$billingAddress->setEmail($client->getUsername() . '@gmail.com');
		$billingAddress->setPostalCode("00001");
		//City
		$cityCodeCollection = $this->container->get('webapp.manager.city_manager')->getBy(array());
		$cityCodeCollectionSize = (count($cityCodeCollection) - 1);
		$randValue = rand(0, $cityCodeCollectionSize);
		$billingAddress->setCity($cityCodeCollection[$randValue]);
		$manager->persist($billingAddress);
		$client->setBillingAddress($billingAddress);

		if($boolean){
			$client->setLogo("logo1.png");
			//Local Address
			$localAddress = new LocalAddress();
			$localAddress->setAddress('Otra Calle Nosequé N13 puerta 20');
			$localAddress->setContact('Otro Nombre de contacto');
			$localAddress->setPhone(md5(time()));
			$localAddress->setEmail($client->getUsername() . '_otro@gmail.com');
			$localAddress->setPostalCode('00001');
			//City
			$randValue = rand(0, $cityCodeCollectionSize);
			$localAddress->setCity($cityCodeCollection[$randValue]);
			$manager->persist($localAddress);
			$client->setLocalAddress($localAddress);
		}else{
			$client->setLogo("logo2.png");
			//Local Address
			$localAddress = new LocalAddress();
			$localAddress->setAddress($billingAddress->getAddress());
			$localAddress->setContact($billingAddress->getContact());
			$localAddress->setPhone($billingAddress->getPhone());
			$localAddress->setEmail($billingAddress->getEmail());
			$localAddress->setPostalCode($billingAddress->getPostalCode());
			//City
			$localAddress->setCity($billingAddress->getCity());
			$manager->persist($localAddress);
			$client->setLocalAddress($localAddress);
		}
		$manager->persist($client);
	}

	private function setFiles($clientCollection)
	{
		foreach ($clientCollection as $client) {
			$this->setFilesByClient($client);
		}
	}

	private function setFilesByClient(Client $client)
	{
		$manager = $this->container->get('doctrine.orm.default_entity_manager');
		$numberImages = rand(0, 3);
		for($i = 0; $i <= $numberImages; $i++) {
			$fileDoc = new FileDoc();
			$fileDoc->setClient($client);
			$fileDoc->setFile("image00".$i.'.jpeg');
			$manager->persist($fileDoc);
		}
		$manager->persist($client);
	}

	private function setPlan($clientCollection)
	{
		foreach ($clientCollection as $client) {
			$this->setPlanByClient($client);
		}
	}

	private function setPlanByClient(Client $client)
	{
		$manager = $this->container->get('doctrine.orm.default_entity_manager');
		$planCollection = $this->container->get('webapp.manager.plan_manager')->getBy(array());
		$planCollectionSize = (count($planCollection) - 1);
		$randValue = rand(0, $planCollectionSize);
		$client->setPlan($planCollection[$randValue]);
		$manager->persist($client);
	}

	/**
	 * Get the order of this fixture
	 *
	 * @return integer
	 */
	public function getOrder()
	{
		return 20;
	}
}