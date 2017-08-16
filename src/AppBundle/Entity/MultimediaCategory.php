<?php

namespace AppBundle\Entity;

use AppBundle\Services\Formatting;
use AppBundle\Services\Slugify;
use Doctrine\Common\Collections\ArrayCollection;
use Symfony\Component\Validator\Constraints as Assert;
use Doctrine\ORM\Mapping as ORM;

/**
 * MultimediaCategory
 *
 * @ORM\Table(name="multimedia_category")
 * @ORM\Entity(repositoryClass="AppBundle\Repository\MultimediaCategoryRepository")
 */
class MultimediaCategory extends Timestampable
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
     * @ORM\Column(name="name", type="string", length=255, unique=true)
     */
    private $name;

    /**
     * @var string
     *
     * @ORM\Column(name="format_name", type="string", length=255, unique=true, nullable=true)
     */
    private $formatName;

    /**
     * @var string
     *
     * @ORM\Column(name="slug", type="string", length=255, unique=true, nullable=true)
     */
    private $slug;

    /**
     * @var ArrayCollection
     *
     * @ORM\OneToMany(targetEntity="Multimedia", mappedBy="category", cascade={"persist"})
     */
    private $multimedias;

    public function __construct()
    {
        parent::__construct();
        $this->multimedias = new ArrayCollection();
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
     * Set name
     *
     * @param string $name
     * @return MultimediaCategory
     */
    public function setName($name)
    {
        $this->name = $name;
        $this->setFormatName($name);
        $this->setSlug($name);

        return $this;
    }

    /**
     * Get name
     *
     * @return string 
     */
    public function getName()
    {
        return $this->name;
    }

    /**
     * @return string
     */
    public function getFormatName()
    {
        return $this->formatName;
    }

    /**
     * @param string $name
     * @return MultimediaCategory
     */
    public function setFormatName($name)
    {
        $this->formatName = Formatting::formatString($name);

        return $this;
    }

    /**
     * @return string
     */
    public function getSlug()
    {
        return $this->slug;
    }

    /**
     * @param string $name
     * @return MultimediaCategory
     */
    public function setSlug($name)
    {
        $this->slug = Slugify::slug($name);

        return $this;
    }

    /**
     * @return ArrayCollection
     */
    public function getMultimedias()
    {
        return $this->multimedias;
    }

    /**
     * @param ArrayCollection $multimedias
     */
    public function setMultimedias($multimedias)
    {
        $this->multimedias = $multimedias;
    }

    /**
     * @param $multimedia
     */
    public function addMultimedia($multimedia)
    {
        if ($this->multimedias->contains($multimedia)){
            return;
        }

        $this->multimedias->add($multimedia);
    }

    /**
     * @param $multimedia
     */
    public function removeMultimedia($multimedia)
    {
        if (!$this->multimedias->contains($multimedia)){
            return;
        }

        $this->multimedias->remove($multimedia);
    }

    public function __toString()
    {
        return $this->getName();
    }
}
