<?php

namespace AppBundle\Twig;

use AppBundle\Entity\Client;
use AppBundle\Entity\FileDoc;
use Symfony\Component\DependencyInjection\ContainerInterface;

class ClientExtension extends \Twig_Extension
{
	private $container;

	public function __construct(ContainerInterface $containerInterface)
	{
	    $this->container = $containerInterface;
	}
	/**
	 * {@inheritdoc}
	 */
	public function getFunctions()
	{
		return array(
			'get_filedoc_by_client' => new \Twig_Function_Method($this, 'getFileDocByClient'),
		);
	}

	public function getFileDocByClient(Client $client, $index)
	{
		$fileDocsCollection = $client->getFileDocs();
		if($fileDocsCollection->containsKey($index)){
			/** @var FileDoc $fileDoc */
			$fileDoc = $fileDocsCollection->get($index);
			return $fileDoc->getFile();
		}

		return "";
	}

	/**
	 * Returns the name of the extension.
	 *
	 * @return string The extension name
	 */
	public function getName()
	{
		return 'client_extension';
	}
}