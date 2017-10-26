<?php

namespace AppBundle\Entity;

use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\HttpFoundation\File\File;
use Vich\UploaderBundle\Mapping\Annotation as Vich;

/**
 * CreativityFile
 *
 * @ORM\Table(name="creativity_file")
 * @ORM\Entity(repositoryClass="AppBundle\Repository\CreativityFileRepository")
 * @Vich\Uploadable
 */
class CreativityFile extends Timestampable implements \Serializable
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
    private $file;

    /**
     * @var File
     *
     * @Vich\UploadableField(mapping="creativities", fileNameProperty="file")
     */
    private $fileVich;

    /**
     * @ORM\ManyToOne(targetEntity="Creativity", inversedBy="fileDocs", cascade={"persist"})
     * @ORM\JoinColumn(name="creativity_id", referencedColumnName="id", onDelete="CASCADE")
     */
    private $creativity;

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
     * Set file
     *
     * @param string $file
     * @return CreativityFile
     */
    public function setFile($file)
    {
        $this->file = $file;

        return $this;
    }

    /**
     * Get file
     *
     * @return string
     */
    public function getFile()
    {
        return $this->file;
    }

    /**
     * Set fileVich
     *
     * @param File|\Symfony\Component\HttpFoundation\File\UploadedFile $fileVich
     * @return CreativityFile
     */
    public function setFileVich(File $fileVich = null)
    {
        $this->fileVich = $fileVich;

        if ($fileVich) {
            $this->updatedAt = new \DateTime('now');
        }

        return $this;
    }

    /**
     * Get fileVich
     *
     * @return File|null
     */
    public function getFileVich()
    {
        return $this->fileVich;
    }

    /**
     * @return Creativity
     */
    public function getCreativity()
    {
        return $this->creativity;
    }

    /**
     * @param Creativity $creativity
     */
    public function setCreativity($creativity)
    {
        $this->creativity = $creativity;
    }

    public function __toString()
    {
        return (string)$this->id;
    }

    /**
     * String representation of object
     * @link http://php.net/manual/en/serializable.serialize.php
     * @return string the string representation of the object or null
     * @since 5.1.0
     */
    public function serialize()
    {
        return serialize(array(
            $this->id,
            $this->file
        ));
    }

    /**
     * Constructs the object
     * @link http://php.net/manual/en/serializable.unserialize.php
     *
     * @param string $serialized <p>
     * The string representation of the object.
     * </p>
     *
     * @return void
     * @since 5.1.0
     */
    public function unserialize($serialized)
    {
        list(
            $this->id,
            $this->file
            ) = unserialize($serialized);
    }
}

