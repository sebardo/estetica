<?php

namespace AppBundle\Entity;

use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\ORM\Mapping as ORM;
use Vich\UploaderBundle\Mapping\Annotation as Vich;
use Symfony\Component\Validator\Constraints as Assert;


/**
 * CreativityProposal
 *
 * @ORM\Table(name="creativity_proposal")
 * @ORM\Entity(repositoryClass="AppBundle\Repository\CreativityProposalRepository")
 */
class CreativityProposal extends Timestampable
{
    //Support
    const SUPPORT_FLYERS = 'flyers';
    const SUPPORT_ROUTERS = 'routers';
    const SUPPORT_ROLLUP = 'roll-up';
    const SUPPORT_OTHER = 'other';

    //MAP CONSTANTS
    const DEFAULT_LAT = "41.385239";
    const DEFAULT_LON = "2.176232";

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
     * @ORM\Column(name="support", type="string", length=255)
     */
    private $support;

    /**
     * @var string
     *
     * @ORM\Column(name="promotion", type="text")
     */
    private $promotion;

    /**
     * @var string
     *
     * @ORM\Column(name="detail", type="text", nullable=true)
     */
    private $detail;

    /**
     * @var int
     *
     * @ORM\Column(name="quantity", type="integer")
     */
    private $quantity;

    /**
     * @var bool
     *
     * @ORM\Column(name="delivery", type="boolean")
     */
    private $delivery;

    /**
     * @var string
     *
     * @ORM\Column(name="delivery_detail", type="string", length=255, nullable=true)
     */
    private $deliveryDetail;

    /**
     * @var string
     *
     * @ORM\Column(name="latitude", type="string", length=255, nullable=true)
     */
    private $latitude;

    /**
     * @var string
     *
     * @ORM\Column(name="longitude", type="string", length=255, nullable=true)
     */
    private $longitude;

    /**
     * @var $map
     */
    private $map;

    /**
     * @var ArrayCollection
     * @ORM\OneToMany(targetEntity="CreativityProposalFile", mappedBy="proposal", cascade={"persist"})
     */
    private $fileDocs;

    public function __construct()
    {
        parent::__construct();
        $this->fileDocs = new ArrayCollection();
        $this->latitude = self::DEFAULT_LAT;
        $this->longitude = self::DEFAULT_LON;
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
     * Set support
     *
     * @param string $support
     * @return CreativityProposal
     */
    public function setSupport($support)
    {
        $this->support = $support;

        return $this;
    }

    /**
     * Get support
     *
     * @return string 
     */
    public function getSupport()
    {
        return $this->support;
    }

    public static function getSelectSupports()
    {
        return array(
            self::SUPPORT_FLYERS => 'app.support.flyers',
            self::SUPPORT_ROUTERS => 'app.support.routers',
            self::SUPPORT_ROLLUP => 'app.support.rollup',
            self::SUPPORT_OTHER => 'app.support.other'
        );
    }

    /**
     * Set promotion
     *
     * @param string $promotion
     * @return CreativityProposal
     */
    public function setPromotion($promotion)
    {
        $this->promotion = $promotion;

        return $this;
    }

    /**
     * Get promotion
     *
     * @return string 
     */
    public function getPromotion()
    {
        return $this->promotion;
    }

    /**
     * Set detail
     *
     * @param string $detail
     * @return CreativityProposal
     */
    public function setDetail($detail)
    {
        $this->detail = $detail;

        return $this;
    }

    /**
     * Get detail
     *
     * @return string 
     */
    public function getDetail()
    {
        return $this->detail;
    }

    /**
     * Set quantity
     *
     * @param integer $quantity
     * @return CreativityProposal
     */
    public function setQuantity($quantity)
    {
        $this->quantity = $quantity;

        return $this;
    }

    /**
     * Get quantity
     *
     * @return integer 
     */
    public function getQuantity()
    {
        return $this->quantity;
    }

    /**
     * Set delivery
     *
     * @param boolean $delivery
     * @return CreativityProposal
     */
    public function setDelivery($delivery)
    {
        $this->delivery = $delivery;

        return $this;
    }

    /**
     * Get delivery
     *
     * @return boolean 
     */
    public function getDelivery()
    {
        return $this->delivery;
    }

    /**
     * Set deliveryDetail
     *
     * @param string $deliveryDetail
     * @return CreativityProposal
     */
    public function setDeliveryDetail($deliveryDetail)
    {
        $this->deliveryDetail = $deliveryDetail;

        return $this;
    }

    /**
     * Get deliveryDetail
     *
     * @return string 
     */
    public function getDeliveryDetail()
    {
        return $this->deliveryDetail;
    }

    /**
     * @return string
     */
    public function getLatitude()
    {
        return $this->latitude;
    }

    /**
     * @param string $latitude
     */
    public function setLatitude($latitude)
    {
        $this->latitude = $latitude;
    }

    /**
     * @return string
     */
    public function getLongitude()
    {
        return $this->longitude;
    }

    /**
     * @param string $longitude
     */
    public function setLongitude($longitude)
    {
        $this->longitude = $longitude;
    }

    public function getMap()
    {
        return $this->latitude . '@' . $this->longitude;
    }

    /**
     * @param $map
     *
     * @return $this
     */
    public function setMap($map)
    {
        $this->map = explode('@', $map, 2);
        $this->setLatitude($this->map[0]);
        $this->setLongitude($this->map[1]);

        return $this;
    }

    /**
     * @param ArrayCollection $fileDocs
     */
    public function setFileDocs($fileDocs)
    {
        $this->fileDocs = $fileDocs;
    }

    /**
     * @param CreativityProposalFile $fileDoc
     */
    public function addFileDoc(CreativityProposalFile $fileDoc)
    {
        if ($this->fileDocs->contains($fileDoc)){
            return;
        }

        $this->fileDocs->add($fileDoc);
        $fileDoc->setProposal($this);
    }

    /**
     * @return ArrayCollection
     */
    public function getFileDocs()
    {
        return $this->fileDocs;
    }

    /**
     * @param CreativityProposalFile $fileDoc
     */
    public function removeFileDoc(CreativityProposalFile $fileDoc)
    {
        if (!$this->fileDocs->contains($fileDoc)){
            return;
        }

        $this->fileDocs->remove($fileDoc);
    }

    public function __toString()
    {
        return (string)$this->id;
    }
}
