<?php

namespace AppBundle\Twig;

use AppBundle\Entity\Client;
use AppBundle\Entity\Creativity;
use AppBundle\Entity\CreativityFile;
use AppBundle\Entity\CreativityProposal;
use AppBundle\Entity\CreativityProposalFile;
use AppBundle\Entity\FileDoc;
use Symfony\Component\DependencyInjection\ContainerInterface;

class CreativityExtension extends \Twig_Extension
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
			'get_filedoc_by_creativity' => new \Twig_Function_Method($this, 'getFileDocByCreativity'),
			'get_filedoc_by_creativity_proposal' => new \Twig_Function_Method($this, 'getFileDocByCreativityProposal'),
		);
	}

	public function getFileDocByCreativity(Creativity $creativity, $index)
	{
		$fileDocsCollection = $creativity->getFileDocs();
		if($fileDocsCollection->containsKey($index)){
			/** @var CreativityFile $fileDoc */
			$fileDoc = $fileDocsCollection->get($index);
			return $fileDoc->getFile();
		}

		return "";
	}

	public function getFileDocByCreativityProposal(CreativityProposal $proposal, $index)
	{
		$fileDocsCollection = $proposal->getFileDocs();
		if($fileDocsCollection->containsKey($index)){
			/** @var CreativityProposalFile $fileDoc */
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
		return 'creativity_extension';
	}
}