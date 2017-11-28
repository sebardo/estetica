<?php

namespace AppBundle\Entity;

use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Validator\Constraints as Assert;
use Doctrine\Common\Collections\ArrayCollection;
use AppBundle\Entity\Image;

/**
 * HomeImages Entity class
 *
 * @ORM\Table(name="home_images")
 * @ORM\Entity()
 */
class HomeImages
{
    /**
     * @var integer
     *
     * @ORM\Column(name="id", type="integer")
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="AUTO")
     */
    private $id;

    /**
     * @var Image
     *
     * @ORM\OneToOne(targetEntity="AppBundle\Entity\Image", cascade={"persist", "remove"})
     * @ORM\JoinColumn(onDelete="set null")
     */
    private $image;
    
    
    /**
     * @var Image
     *
     * @ORM\OneToOne(targetEntity="AppBundle\Entity\Image", cascade={"persist", "remove"})
     * @ORM\JoinColumn(onDelete="set null")
     */
    private $image2;
    
    /**
     * @var Image
     *
     * @ORM\OneToOne(targetEntity="AppBundle\Entity\Image", cascade={"persist", "remove"})
     * @ORM\JoinColumn(onDelete="set null")
     */
    private $image3;

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
     * Set image
     *
     * @param Image $image
     *
     * @return Brand
     */
    public function setImage(Image $image = null)
    {
        $this->image = $image;

        return $this;
    }

    /**
     * Get image
     *
     * @return Image
     */
    public function getImage()
    {
        return $this->image;
    }
    
    /**
     * Set image2
     *
     * @param Image $image2
     *
     * @return Brand
     */
    public function setImage2(Image $image2 = null)
    {
        $this->image2 = $image2;

        return $this;
    }

    /**
     * Get image2
     *
     * @return Image
     */
    public function getImage2()
    {
        return $this->image2;
    }
    
    /**
     * Set image
     *
     * @param Image $image3
     *
     * @return Brand
     */
    public function setImage3(Image $image3 = null)
    {
        $this->image3 = $image3;

        return $this;
    }

    /**
     * Get image
     *
     * @return Image
     */
    public function getImage3()
    {
        return $this->image3;
    }
}