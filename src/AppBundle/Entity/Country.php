<?php

namespace AppBundle\Entity;

use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\ORM\Mapping as ORM;

/**
 * Country
 *
 * @ORM\Table(name="country")
 * @ORM\Entity(repositoryClass="AppBundle\Repository\CountryRepository")
 */
class Country
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
     * @ORM\Column(name="name", type="string", length=255)
     */
    private $name;

    /**
     * @var string
     *
     * @ORM\Column(name="slug", type="string", length=255, unique=true)
     */
    private $slug;

    /**
     * @var ArrayCollection
     *
     * @ORM\OneToMany(targetEntity="Province", mappedBy="country", cascade={"persist"})
     */
    private $provinces;

    public function __construct()
    {
        $this->provinces = new ArrayCollection();
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
     * @return Country
     */
    public function setName($name)
    {
        $this->name = $name;

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
     * Set slug
     *
     * @param string $slug
     * @return Country
     */
    public function setSlug($slug)
    {
        $this->slug = $slug;

        return $this;
    }

    /**
     * Get slug
     *
     * @return string 
     */
    public function getSlug()
    {
        return $this->slug;
    }

    /**
     * @return ArrayCollection
     */
    public function getProvinces()
    {
        return $this->provinces;
    }

    /**
     * @param ArrayCollection $provinces
     */
    public function setProvinces($provinces)
    {
        $this->provinces = $provinces;
    }

    /**
     * Add provinces
     *
     * @param  Province $province
     */
    public function addProvince(Province $province)
    {
        if ($this->provinces->contains($province)){
            return;
        }

        $this->provinces->add($province);
    }

    /**
     * Remove provinces
     *
     * @param Province $province
     */
    public function removeProvince(Province $province)
    {
        if (!$this->provinces->contains($province)){
            return;
        }

        $this->provinces->removeElement($province);
    }

    public function __toString()
    {
        return $this->name;
    }
}
