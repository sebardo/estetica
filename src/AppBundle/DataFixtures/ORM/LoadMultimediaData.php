<?php


namespace AppBundle\DataFixtures\ORM;


use AppBundle\Entity\Multimedia;
use Doctrine\Common\DataFixtures\AbstractFixture;
use Doctrine\Common\DataFixtures\OrderedFixtureInterface;
use Doctrine\Common\Persistence\ObjectManager;
use Symfony\Component\DependencyInjection\ContainerAwareInterface;
use Symfony\Component\DependencyInjection\ContainerInterface;

class LoadMultimediaData extends AbstractFixture implements OrderedFixtureInterface, ContainerAwareInterface
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
		$boolean = true;
		for($i = 0; $i < 48; $i++){
			$entity = new Multimedia();
			$multimediaCategoryCollection = $this->container->get('webapp.manager.multimedia_category_manager')->getBy(array());
			$multimediaCategoryCollectionSize = (count($multimediaCategoryCollection) - 1);
			$randValue = rand(0, $multimediaCategoryCollectionSize);
			$entity->setCategory($multimediaCategoryCollection[$randValue]);
			$entity->setTitle('multimedia_example_' . $i);
			$entity->setImage('logo1.png');
			if($boolean){
				$entity->setFile('example.pdf');
				$boolean = false;
			}else{
				$entity->setUrlVideo('https://www.youtube.com/embed/gJ4DAV7KVyQ');
				$boolean = true;
			}
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
		return 40;
	}
}