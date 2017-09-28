<?php

namespace AppBundle\Entity;

use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\ORM\Mapping as ORM;
use Vich\UploaderBundle\Mapping\Annotation as Vich;
use Symfony\Component\Validator\Constraints as Assert;

/**
 * Creativity
 *
 * @ORM\Table(name="creativity")
 * @ORM\Entity(repositoryClass="AppBundle\Repository\CreativityRepository")
 */
class Creativity extends Timestampable
{
    //Support
    const SUPPORT_FLYERS = 'flyers';
    const SUPPORT_ROUTERS = 'routers';
    const SUPPORT_ROLLUP = 'roll-up';
    //Creativity Fields
    //FLYER
    const FLYER_OBVERSE_HEADER = 'flyer-obverse-header';
    const FLYER_OBVERSE_CONTENT_1 = 'flyer-obverse-content-1';
    const FLYER_OBVERSE_PRIZE_1 = 'flyer-obverse-prize-1';
    const FLYER_OBVERSE_CONTENT_2 = 'flyer-obverse-content-2';
    const FLYER_OBVERSE_PRIZE_2 = 'flyer-obverse-prize-2';
    const FLYER_OBVERSE_FOOTER = 'flyer-obverse-footer';
    const FLYER_BACK_HEADER = 'flyer-back-header';
    const FLYER_BACK_DESCRIPTION = 'flyer-back-description';
    const FLYER_BACK_FOOTER = 'flyer-back-footer';
    //ROUTER
    const ROUTER_OBVERSE_HEADER = 'router-obverse-header';
    const ROUTER_OBVERSE_CONTENT_1 = 'router-obverse-content-1';
    const ROUTER_OBVERSE_PRIZE_1 = 'router-obverse-prize-1';
    const ROUTER_OBVERSE_CONTENT_2 = 'router-obverse-content-2';
    const ROUTER_OBVERSE_PRIZE_2 = 'router-obverse-prize-2';
    const ROUTER_OBVERSE_FOOTER = 'router-obverse-footer';
    //ROLLUP
    const ROLLUP_OBVERSE_HEADER = 'rollup-obverse-header';
    const ROLLUP_OBVERSE_CONTENT_1 = 'rollup-obverse-content-1';
    const ROLLUP_OBVERSE_CONTENT_2 = 'rollup-obverse-content-2';
    const ROLLUP_OBVERSE_CONTENT_3 = 'rollup-obverse-content-3';
    const ROLLUP_OBVERSE_FOOTER = 'rollup-obverse-footer';
    //Category
    const CATEGORY_TECHNOLOGY = 'technology';
    const CATEGORY_MONTH = 'month';
    const CATEGORY_CAMPAIGN = 'campaign';
    const CATEGORY_PACK = 'packs';
    //SubCategory TECHNOLOGY
    const SUBCATEGORY_TECHNOLOGY_1 = 'criolopolisis';
    const SUBCATEGORY_TECHNOLOGY_2 = 'ipl-fotodepilacion';
    const SUBCATEGORY_TECHNOLOGY_3 = 'laser-depilacion';
    const SUBCATEGORY_TECHNOLOGY_4 = 'radiofrecuencia';
    const SUBCATEGORY_TECHNOLOGY_5 = 'cavitacion';
    const SUBCATEGORY_TECHNOLOGY_6 = 'hifu';
    const SUBCATEGORY_TECHNOLOGY_7 = 'narl';
    //SubCategory MONTH
    const SUBCATEGORY_MONTH_1 = 'enero';
    const SUBCATEGORY_MONTH_2 = 'febrero';
    const SUBCATEGORY_MONTH_3 = 'marzo';
    const SUBCATEGORY_MONTH_4 = 'abril';
    const SUBCATEGORY_MONTH_5 = 'mayo';
    const SUBCATEGORY_MONTH_6 = 'junio';
    const SUBCATEGORY_MONTH_7 = 'julio';
    const SUBCATEGORY_MONTH_8 = 'agosto';
    const SUBCATEGORY_MONTH_9 = 'septiembre';
    const SUBCATEGORY_MONTH_10 = 'octubre';
    const SUBCATEGORY_MONTH_11 = 'noviembre';
    const SUBCATEGORY_MONTH_12 = 'diciembre';
    //SubCategory CAMPAIGN
    const SUBCATEGORY_CAMPAIGN_1 = 'dia-padre';
    const SUBCATEGORY_CAMPAIGN_2 = 'dia-madre';
    const SUBCATEGORY_CAMPAIGN_3 = 'san-valentin';
    const SUBCATEGORY_CAMPAIGN_4 = 'verano';
    const SUBCATEGORY_CAMPAIGN_5 = 'navidad';
    const SUBCATEGORY_CAMPAIGN_6 = 'otras';

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
     * @ORM\Column(name="support", type="string", length=255)
     */
    private $support;

    /**
     * @var string
     *
     * @ORM\Column(name="category", type="string", length=255)
     */
    private $category;

    /**
     * @var string
     *
     * @ORM\Column(name="subcategory", type="string", length=255, nullable=true)
     */
    private $subcategory;

    /**
     * @var ArrayCollection
     * @ORM\OneToMany(targetEntity="CreativityFile", mappedBy="creativity", cascade={"persist"})
     */
    private $fileDocs;

    /**
     * @var ArrayCollection
     * @ORM\OneToMany(targetEntity="CreativityFileRaw", mappedBy="creativity", cascade={"persist"})
     */
    private $fileDocsRaw;

    /**
     * @var ArrayCollection
     * @ORM\OneToMany(targetEntity="CreativityOrder", mappedBy="creativity", cascade={"persist"})
     */
    private $creativityOrders;

    public function __construct()
    {
        parent::__construct();
        $this->fileDocs = new ArrayCollection();
        $this->fileDocsRaw = new ArrayCollection();
        $this->creativityOrders = new ArrayCollection();
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
    public function getName()
    {
        return $this->name;
    }

    /**
     * @param string $name
     */
    public function setName($name)
    {
        $this->name = $name;
    }

    /**
     * Set support
     *
     * @param string $support
     * @return Creativity
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
        );
    }

    public static function createSupportFieldArray()
    {
        return $response = array(
            self::SUPPORT_FLYERS => array(
                self::FLYER_OBVERSE_HEADER,
                self::FLYER_OBVERSE_CONTENT_1,
                self::FLYER_OBVERSE_PRIZE_1,
                self::FLYER_OBVERSE_CONTENT_2,
                self::FLYER_OBVERSE_PRIZE_2,
                self::FLYER_OBVERSE_FOOTER,
                self::FLYER_BACK_HEADER,
                self::FLYER_BACK_DESCRIPTION,
                self::FLYER_BACK_FOOTER,
            ),
            self::SUPPORT_ROUTERS => array(
                self::ROUTER_OBVERSE_HEADER,
                self::ROUTER_OBVERSE_CONTENT_1,
                self::ROUTER_OBVERSE_PRIZE_1,
                self::ROUTER_OBVERSE_CONTENT_2,
                self::ROUTER_OBVERSE_PRIZE_2,
                self::ROUTER_OBVERSE_FOOTER,
            ),
            self::SUPPORT_ROLLUP => array(
                self::ROLLUP_OBVERSE_HEADER,
                self::ROLLUP_OBVERSE_CONTENT_1,
                self::ROLLUP_OBVERSE_CONTENT_2,
                self::ROLLUP_OBVERSE_CONTENT_3,
                self::ROLLUP_OBVERSE_FOOTER,
            )
        );
    }

    public static function getSupportFields($support)
    {
        return (array_key_exists($support, self::createSupportFieldArray())) ? self::createSupportFieldArray()[$support] : array();
    }

    /**
     * @return string
     */
    public function getCategory()
    {
        return $this->category;
    }

    /**
     * @param string $category
     */
    public function setCategory($category)
    {
        $this->category = $category;
    }

    /**
     * @return string
     */
    public function getSubcategory()
    {
        return $this->subcategory;
    }

    /**
     * @param string $subcategory
     */
    public function setSubcategory($subcategory)
    {
        $this->subcategory = $subcategory;
    }

    public static function getSelectCategories()
    {
        return array(
            self::CATEGORY_TECHNOLOGY => 'app.category.technology.name',
            self::CATEGORY_MONTH => 'app.category.month.name',
            self::CATEGORY_CAMPAIGN => 'app.category.campaign.name',
            self::CATEGORY_PACK => 'app.category.pack.name'
        );
    }

    public static function getSelectSubcategories($category)
    {
        switch($category){
            case self::CATEGORY_TECHNOLOGY:
                $response = array(
                    self::SUBCATEGORY_TECHNOLOGY_1 => 'app.category.technology.1.name',
                    self::SUBCATEGORY_TECHNOLOGY_2 => 'app.category.technology.2.name',
                    self::SUBCATEGORY_TECHNOLOGY_3 => 'app.category.technology.3.name',
                    self::SUBCATEGORY_TECHNOLOGY_4 => 'app.category.technology.4.name',
                    self::SUBCATEGORY_TECHNOLOGY_5 => 'app.category.technology.5.name',
                    self::SUBCATEGORY_TECHNOLOGY_6 => 'app.category.technology.6.name',
                    self::SUBCATEGORY_TECHNOLOGY_7 => 'app.category.technology.7.name'
                );
                break;
            case self::CATEGORY_MONTH:
                $response = array(
                    self::SUBCATEGORY_MONTH_1 => 'app.category.month.1.name',
                    self::SUBCATEGORY_MONTH_2 => 'app.category.month.2.name',
                    self::SUBCATEGORY_MONTH_3 => 'app.category.month.3.name',
                    self::SUBCATEGORY_MONTH_4 => 'app.category.month.4.name',
                    self::SUBCATEGORY_MONTH_5 => 'app.category.month.5.name',
                    self::SUBCATEGORY_MONTH_6 => 'app.category.month.6.name',
                    self::SUBCATEGORY_MONTH_7 => 'app.category.month.7.name',
                    self::SUBCATEGORY_MONTH_8 => 'app.category.month.8.name',
                    self::SUBCATEGORY_MONTH_9 => 'app.category.month.9.name',
                    self::SUBCATEGORY_MONTH_10 => 'app.category.month.10.name',
                    self::SUBCATEGORY_MONTH_11 => 'app.category.month.11.name',
                    self::SUBCATEGORY_MONTH_12 => 'app.category.month.12.name'
                );
                break;
            case self::CATEGORY_CAMPAIGN:
                $response = array(
                    self::SUBCATEGORY_CAMPAIGN_1 => 'app.category.campaign.1.name',
                    self::SUBCATEGORY_CAMPAIGN_2 => 'app.category.campaign.2.name',
                    self::SUBCATEGORY_CAMPAIGN_3 => 'app.category.campaign.3.name',
                    self::SUBCATEGORY_CAMPAIGN_4 => 'app.category.campaign.4.name',
                    self::SUBCATEGORY_CAMPAIGN_5 => 'app.category.campaign.5.name',
                    self::SUBCATEGORY_CAMPAIGN_6 => 'app.category.campaign.6.name'
                );
                break;
            default:
                $response = array();
                break;
        }

        return $response;
    }

    /**
     * @return ArrayCollection
     */
    public function getFileDocs()
    {
        return $this->fileDocs;
    }

    public function getFileDocsOnArray()
    {
        $response = array();
        foreach ($this->getFileDocs() as $fileDoc) {
            if($fileDoc instanceof CreativityFile){
                $response[] = $fileDoc->getFile();
            }
        }

        return $response;
    }

    /**
     * @param ArrayCollection $fileDocs
     */
    public function setFileDocs($fileDocs)
    {
        $this->fileDocs = $fileDocs;
    }

    /**
     * @param CreativityFile $fileDoc
     */
    public function addFileDoc(CreativityFile $fileDoc)
    {
        if ($this->fileDocs->contains($fileDoc)){
            return;
        }

        $this->fileDocs->add($fileDoc);
        $fileDoc->setCreativity($this);
    }

    /**
     * @param CreativityFile $fileDoc
     */
    public function removeFileDoc(CreativityFile $fileDoc)
    {
        if (!$this->fileDocs->contains($fileDoc)){
            return;
        }

        $this->fileDocs->remove($fileDoc);
    }

    /**
     * @return ArrayCollection
     */
    public function getFileDocsRaw()
    {
        return $this->fileDocsRaw;
    }

    public function getFileDocsRawOnArray()
    {
        $response = array();
        foreach ($this->getFileDocsRaw() as $fileDocRaw) {
            if($fileDocRaw instanceof CreativityFileRaw){
                $response[] = $fileDocRaw->getFile();
            }
        }

        return $response;
    }

    /**
     * @param ArrayCollection $fileDocsRaw
     */
    public function setFileDocsRaw($fileDocsRaw)
    {
        $this->fileDocsRaw = $fileDocsRaw;
    }

    /**
     * @param CreativityFileRaw $fileDocRaw
     */
    public function addFileDocRaw(CreativityFileRaw $fileDocRaw)
    {
        if ($this->fileDocs->contains($fileDocRaw)){
            return;
        }

        $this->fileDocsRaw->add($fileDocRaw);
        $fileDocRaw->setCreativity($this);
    }

    /**
     * @param CreativityFileRaw $fileDocRaw
     */
    public function removeFileDocRaw(CreativityFileRaw $fileDocRaw)
    {
        if (!$this->fileDocsRaw->contains($fileDocRaw)){
            return;
        }

        $this->fileDocsRaw->remove($fileDocRaw);
    }

    /**
     * @return ArrayCollection
     */
    public function getCreativityOrders()
    {
        return $this->creativityOrders;
    }

    /**
     * @param ArrayCollection $creativityOrders
     */
    public function setCreativityOrders($creativityOrders)
    {
        $this->creativityOrders = $creativityOrders;
    }

    /**
     * @param CreativityOrder $creativityOrder
     */
    public function addCreativityOrder(CreativityOrder $creativityOrder)
    {
        if ($this->creativityOrders->contains($creativityOrder)){
            return;
        }

        $this->creativityOrders->add($creativityOrder);
        $creativityOrder->setCreativity($this);
    }

    /**
     * @param CreativityOrder $creativityOrder
     */
    public function removeCreativityOrder(CreativityOrder $creativityOrder)
    {
        if (!$this->creativityOrders->contains($creativityOrder)){
            return;
        }

        $this->creativityOrders->remove($creativityOrder);
    }

    public function __toString()
    {
        return (string)$this->id;
    }
}
