<?php

namespace AppBundle\Entity;

use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\HttpFoundation\File\File;
use Vich\UploaderBundle\Mapping\Annotation as Vich;
use Symfony\Component\Validator\Constraints as Assert;

/**
 * CreativityOrder
 *
 * @ORM\Table(name="creativity_order")
 * @ORM\Entity(repositoryClass="AppBundle\Repository\CreativityOrderRepository")
 * @Vich\Uploadable
 */
class CreativityOrder extends Timestampable
{
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
     * @ORM\Column(name="creativityOrderPdf", type="string", length=255, nullable=true)
     */
    private $creativityOrderPdf;

    /**
     * @var File
     *
     * @Vich\UploadableField(mapping="creativity_orders", fileNameProperty="creativityOrderPdf")
     */
    private $creativityOrderPdfFile;

    /**
     * @var bool
     *
     * @ORM\Column(name="delivery", type="boolean")
     */
    private $delivery;

    /**
     * @var string
     *
     * @ORM\Column(name="delivery_detail", type="text", nullable=true)
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
     * @var $fields
     */
    private $fields;

    /**
     * @var string
     * @ORM\Column(name="fields_value", type="text", length=255, nullable=true)
     */
    private $fieldsValue;

    /**
     * @var bool
     *
     * @ORM\Column(name="print", type="boolean")
     */
    private $print;

    /**
     * @ORM\ManyToOne(targetEntity="Creativity", inversedBy="creativityOrders", cascade={"persist"})
     * @ORM\JoinColumn(name="creativiy_id", referencedColumnName="id", onDelete="CASCADE")
     */
    private $creativity;

    /**
     * @ORM\ManyToOne(targetEntity="Client", inversedBy="creativityOrders", cascade={"persist"})
     * @ORM\JoinColumn(name="client_id", referencedColumnName="id", onDelete="CASCADE")
     */
    private $client;

    public function __construct(Creativity $creativity, Client $client)
    {
        parent::__construct();
        $this->creativity = $creativity;
        $this->client = $client;
        $this->delivery = false;
        $this->print = false;
        $this->latitude = self::DEFAULT_LAT;
        $this->longitude = self::DEFAULT_LON;
        $this->createDefaultFieldsFooterValues();
    }

    public function createDefaultFieldsFooterValues()
    {
        $response = array();
        foreach ($this->getFields() as $field) {
            $response[$field] = '';
            if(strpos($field, 'footer') !== false){
                $response[$field] = $this->client->getTradeName();
            }
        }

        $this->setFieldsValue($response);
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
     * @return string
     */
    public function getCreativityOrderPdf()
    {
        return $this->creativityOrderPdf;
    }

    /**
     * @param string $creativityOrderPdf
     */
    public function setCreativityOrderPdf($creativityOrderPdf)
    {
        $this->creativityOrderPdf = $creativityOrderPdf;
    }

    /**
     * @return File
     */
    public function getCreativityOrderPdfFile()
    {
        return $this->creativityOrderPdfFile;
    }

    /**
     * @param File|null $creativityOrderPdfFile
     */
    public function setCreativityOrderPdfFile(File $creativityOrderPdfFile = null)
    {
        $this->creativityOrderPdfFile = $creativityOrderPdfFile;

        if($creativityOrderPdfFile) {
            $this->updatedAt = new \DateTime('now');
        }
    }

    /**
     * Set delivery
     *
     * @param boolean $delivery
     * @return CreativityOrder
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
     * @return CreativityOrder
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
     * Set latitude
     *
     * @param string $latitude
     * @return CreativityOrder
     */
    public function setLatitude($latitude)
    {
        $this->latitude = $latitude;

        return $this;
    }

    /**
     * Get latitude
     *
     * @return string 
     */
    public function getLatitude()
    {
        return $this->latitude;
    }

    /**
     * Set longitude
     *
     * @param string $longitude
     * @return CreativityOrder
     */
    public function setLongitude($longitude)
    {
        $this->longitude = $longitude;

        return $this;
    }

    /**
     * Get longitude
     *
     * @return string 
     */
    public function getLongitude()
    {
        return $this->longitude;
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

    /**
     * @return Client
     */
    public function getClient()
    {
        return $this->client;
    }

    /**
     * @param Client $client
     */
    public function setClient($client)
    {
        $this->client = $client;
    }

    /**
     * @return mixed
     */
    public function getFields()
    {
        return Creativity::getSupportFields($this->getCreativity()->getSupport());
    }

    /**
     * @param mixed $fields
     */
    public function setFields($fields)
    {
        $this->fields = $fields;
    }

    /**
     * @return array
     */
    public function getFieldsValue()
    {
        return json_decode($this->fieldsValue, true);
    }

    /**
     * @param array $fieldsValue
     */
    public function setFieldsValue($fieldsValue)
    {
        $this->fieldsValue = json_encode($fieldsValue);
    }

    /**
     * Set print
     *
     * @param boolean $print
     * @return CreativityOrder
     */
    public function setPrint($print)
    {
        $this->print = $print;

        return $this;
    }

    /**
     * Get print
     *
     * @return boolean 
     */
    public function getPrint()
    {
        return $this->print;
    }

    public function __toString()
    {
        return (string)$this->id;
    }
}
