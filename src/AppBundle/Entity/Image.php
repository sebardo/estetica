<?php

namespace AppBundle\Entity;

use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\HttpFoundation\File\File;
use Vich\UploaderBundle\Mapping\Annotation as Vich;

/**
 * Image
 *
 * @ORM\Table(name="image")
 * @ORM\Entity(repositoryClass="AppBundle\Repository\ImageRepository")
 * @Vich\Uploadable
 */
class Image extends Timestampable
{
	/**
	 * @var int
	 *
	 * @ORM\Column(name="id", type="integer")
	 * @ORM\Id
	 * @ORM\GeneratedValue(strategy="AUTO")
	 */
	private $id;

	/**
	 * @var string
	 *
	 * @ORM\Column(name="file", type="string", length=255)
	 */
	private $fileName;

	/**
	 * @var File
	 *
	 * @Vich\UploadableField(mapping="registrations", fileNameProperty="fileName")
	 */
	private $fileVich;

	/**
	 * @ORM\OneToOne(targetEntity="Registration", mappedBy="image", cascade={"persist"})
	 */
	private $registration;

	public function __construct()
	{
		parent::__construct();
	}

	/**
	 * Get id
	 *
	 * @return integer
	 */
	public function getId()
	{
		return $this->id;
	}

	/**
	 * Set fileName
	 *
	 * @param string $fileName
	 * @return Image
	 */
	public function setFileName($fileName)
	{
		$this->fileName = $fileName;

		return $this;
	}

	/**
	 * Get file
	 *
	 * @return string
	 */
	public function getFileName()
	{
		return $this->fileName;
	}

	/**
	 * Set fileVich
	 *
	 * @param File|null $fileVich
	 */
	public function setFileVich(File $fileVich = null)
	{
		$this->fileVich = $fileVich;

		if ($fileVich) {
			$this->updatedAt = new \DateTime('now');
		}
	}

	/**
	 * Get fileVich
	 *
	 * @return Image
	 */
	public function getFileVich()
	{
		return $this->fileVich;
	}

	/**
	 * @return Registration
	 */
	public function getRegistration()
	{
		return $this->registration;
	}

	/**
	 * @param Registration $registration
	 */
	public function setRegistration($registration)
	{
		$this->registration = $registration;
	}

	public function __toString()
	{
		return $this->fileName;
	}
}
