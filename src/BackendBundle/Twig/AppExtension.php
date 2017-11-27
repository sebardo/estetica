<?php


namespace BackendBundle\Twig;

use Symfony\Component\DependencyInjection\ContainerInterface;
use Symfony\Component\Validator\Constraints\DateTime;


class AppExtension extends \Twig_Extension {

	/** @var ContainerInterface */
	protected $container;

	/** @param ContainerInterface $container */
	public function __construct(ContainerInterface $container)
	{
		$this->container = $container;
	}
    
    /**
     * {@inheritdoc}
     */
    public function getFunctions()
    {
        return array(
            new \Twig_SimpleFunction('get_slider_items', array($this, 'getSliderItems')),
            new \Twig_SimpleFunction('get_home_images', array($this, 'getHomeImages')),
        );
    }

	public function getFilters()
	{
		return array(
			new \Twig_SimpleFilter('isDateTime', array($this, 'isDateTime')),
		);
	}

	public function isDateTime($object)
	{
		return $object instanceof \DateTime;
	}

    /**
     * {@inheritDoc}
     */
    public function getSliderItems()
    {
        $em = $this->container->get('doctrine')->getManager();
        $headers = $em->getRepository("AppBundle:Slider")->findBy(array('active' => true), array('order' => 'ASC'));
        return $headers;
    }
    
    /**
     * {@inheritDoc}
     */
    public function getHomeImages()
    {
        $em = $this->container->get('doctrine')->getManager();
        $headers = $em->getRepository("AppBundle:HomeImages")->findBy(array(), array());
        if(isset($headers[0])) return $headers[0];
        return null;
    }
    
	/**
	 * Returns the name of the extension.
	 *
	 * @return string The extension name
	 */
	public function getName()
	{
		return 'app_extension';
	}
}