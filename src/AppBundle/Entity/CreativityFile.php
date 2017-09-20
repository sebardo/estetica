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
class CreativityFile extends Timestampable
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
     * @return FileDoc
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
     * @return File
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
}

