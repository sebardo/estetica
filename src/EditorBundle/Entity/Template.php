<?php

namespace EditorBundle\Entity;

use DateTime;
use Doctrine\ORM\Mapping as ORM;
use Gedmo\Mapping\Annotation as Gedmo;
use Symfony\Component\Validator\Constraints as Assert;
use AppBundle\Entity\Client;
use Doctrine\Common\Collections\ArrayCollection;
use Symfony\Component\HttpFoundation\File\UploadedFile;
use Vich\UploaderBundle\Mapping\Annotation as Vich;

/**
 * Template Entity class
 *
 * @ORM\Table(name="templates")
 * @ORM\Entity(repositoryClass="EditorBundle\Entity\Repository\TemplateRepository")
 * @Vich\Uploadable
 */
class Template 
{
    //Support
    const SUPPORT_FLYERS = 'flyers';
    const SUPPORT_ROUTERS = 'routers';
    const SUPPORT_ROLLUP = 'roll-up';
    
    //MAP CONSTANTS
    const DEFAULT_LAT = "41.385239";
    const DEFAULT_LON = "2.176232";
    
    const CREATED_STATE = 'Created';
    const PENDING_STATE = 'Pending';
    const COMPLETED_STATE = 'Completed';
    
    /**
     * @var integer
     *
     * @ORM\Column(type="integer")
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="AUTO")
     */
    private $id;

    /**
     * @var Client
     *
     * @ORM\ManyToOne(targetEntity="AppBundle\Entity\Client", inversedBy="templates")
     * @ORM\JoinColumn(name="client_id", referencedColumnName="id", nullable=true)
     */
    private $client;

    /**
     * @var string
     *
     * @ORM\Column(type="string", length=255, nullable=true)
     */
    private $name;
    
    /**
     * @var string
     *
     * @ORM\Column(type="string", length=255, nullable=false)
     */
    private $status;
   
    /**
     * @var User
     *
     * @ORM\ManyToOne(targetEntity="AppBundle\Entity\Client")
     * @ORM\JoinColumn(name="creator_id", referencedColumnName="id", nullable=false)
     * @Gedmo\Blameable(on="create")
     */
    private $creator;
            
    /**
     * @var DateTime
     *
     * @ORM\Column(type="datetime", nullable=false)
     * @Gedmo\Timestampable(on="create")
     */
    private $createdAt;
    
    /**
     * @var ArrayCollection
     *
     * @ORM\OneToMany(targetEntity="Template", mappedBy="parentTemplate", cascade={"remove"})
     */
    private $childs;
    
    /**
     * @var Template
     *
     * @ORM\ManyToOne(targetEntity="Template", inversedBy="childs")
     * @ORM\JoinColumn(name="parent_template_id", referencedColumnName="id", nullable=true , onDelete="cascade")
     */
    private $parentTemplate;
    
    /**
     * @var File
     *
     * @Vich\UploadableField(mapping="templates", fileNameProperty="backgroundImage")
     *
     * @Assert\File(
     *     mimeTypes = {"image/jpg", "image/jpeg"},
     *     mimeTypesMessage = "Por favor selecciona una imagen con extensión JPG"
     * )
     */
    private $backgroundImageFile;
    
    /**
     * @var string
     *
     * @ORM\Column(name="backgroundImage", type="string", length=255, nullable=true)
     *
     */
    private $backgroundImage;

    /**
     * @var string
     *
     * @ORM\Column(name="previewImage", type="text", nullable=true)
     */
    private $previewImage;
    
    /**
     * @var File
     *
     * @Vich\UploadableField(mapping="templates", fileNameProperty="backgroundImage2")
     *
     * @Assert\File(
     *     mimeTypes = {"image/jpg", "image/jpeg"},
     *     mimeTypesMessage = "Por favor selecciona una imagen con extensión JPG"
     * )
     */
    private $backgroundImage2File;
    
    /**
     * @var string
     *
     * @ORM\Column(name="backgroundImage2", type="string", length=255, nullable=true)
     */
    private $backgroundImage2;
    
    /**
     * @var string
     *
     * @ORM\Column(name="previewImage2", type="text", nullable=true)
     */
    private $previewImage2;
    
    /**
     * @var string
     *
     * @ORM\Column(name="support", type="string", length=255, nullable=true)
     */
    private $support;

    /**
     * @var string
     *
     * @ORM\Column(name="category", type="string", length=255, nullable=true)
     */
    private $category;

    /**
     * @var string
     *
     * @ORM\Column(name="subcategory", type="string", length=255, nullable=true)
     */
    private $subcategory;
    
    /**
     * @var string
     *
     * @ORM\Column(name="frontPageHtml", type="text", nullable=true)
     */
    private $frontPageHtml;
    
    
    /**
     * @var string
     *
     * @ORM\Column(name="backPageHtml", type="text", nullable=true)
     */
    private $backPageHtml;
    
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
     * @var string
     *
     * @ORM\Column(name="pdfPath", type="string", length=255, nullable=true)
     */
    private $pdfPath;
    
    /**
     * @var bool
     *
     * @ORM\Column(name="print", type="boolean")
     */
    private $print;
    
    /**
     * Constructor
     */
    public function __construct()
    {
        $this->childs = new ArrayCollection();
        $this->setStatus(self::CREATED_STATE);
        $this->delivery = false;
        $this->latitude = self::DEFAULT_LAT;
        $this->longitude = self::DEFAULT_LON;
        $this->print = false;
    }
    /**
     * @return int
     */
    public function getId()
    {
        return $this->id;
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
     *
     * @return Template
     */
    public function setClient(Client $client)
    {
        $this->client = $client;

        return $this;
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
     *
     * @return Template
     */
    public function setName($name)
    {
        $this->name = $name;

        return $this;
    }
    
    /**
     * @return string
     */
    public function getStatus()
    {
        return $this->status;
    }

    /**
     * @param string $status
     *
     * @return Template
     */
    public function setStatus($status)
    {
        $this->status = $status;

        return $this;
    }

    /**
     * @return User
     */
    public function getCreator()
    {
        return $this->creator;
    }

    /**
     * @param Client $creator
     *
     * @return Template
     */
    public function setCreator(Client $creator)
    {
        $this->creator = $creator;

        return $this;
    }

     /**
     * Add child
     *
     * @param Template $child
     *
     * @return Template
     */
    public function addChild(Template $child)
    {
        $this->childs->add($child);

        return $this;
    }

    /**
     * Remove child
     *
     * @param Template $child
     */
    public function removeChild(Template $child)
    {
        $this->childs->removeElement($child);
    }

    /**
     * Get childs
     *
     * @return ArrayCollection
     */
    public function getChilds()
    {
        return $this->childs;
    }

    /**
     * Set parent Template
     *
     * @param Template $parentTemplate
     *
     * @return Template
     */
    public function setParentTemplate(Template $parentTemplate = null)
    {
        if(!is_null($parentTemplate)) $parentTemplate->addChild($this);
        $this->parentTemplate = $parentTemplate;

        return $this;
    }

    /**
     * Get parent Template
     *
     * @return Template
     */
    public function getParentTemplate()
    {
        return $this->parentTemplate;
    }
    
    
    /**
     * Get backgroundImage
     *
     * @return Template
     */
    public function getBackgroundImage()
    {
        return $this->backgroundImage;
    }
    
    /**
     * @param string $backgroundImage
     *
     * @return Template
     */
    public function setBackgroundImage($backgroundImage)
    {
        $this->backgroundImage = $backgroundImage;

        return $this;
    }
    
    /**
     * Get backgroundImageFile
     *
     * @return Template
     */
    public function getBackgroundImageFile()
    {
        return $this->backgroundImageFile;
    }
    
    /**
     * @param string $backgroundImageFile
     *
     * @return Template
     */
    public function setBackgroundImageFile($backgroundImageFile)
    {
        $this->backgroundImageFile = $backgroundImageFile;

        return $this;
    }
    
    /**
     * Get previewImage
     *
     * @return Template
     */
    public function getPreviewImage()
    {
        return $this->previewImage;
    }
    
    /**
     * @param string $previewImage
     *
     * @return Template
     */
    public function setPreviewImage($previewImage)
    {
        $this->previewImage = $previewImage;

        return $this;
    }
    
    /**
     * Get backgroundImage2
     *
     * @return Template
     */
    public function getBackgroundImage2()
    {
        return $this->backgroundImage2;
    }
    
    /**
     * @param string $backgroundImage2
     *
     * @return Template
     */
    public function setBackgroundImage2($backgroundImage2)
    {
        $this->backgroundImage2 = $backgroundImage2;

        return $this;
    }
    
    /**
     * Get backgroundImage2File
     *
     * @return Template
     */
    public function getBackgroundImage2File()
    {
        return $this->backgroundImage2File;
    }
    
    /**
     * @param string $backgroundImage2File
     *
     * @return Template
     */
    public function setBackgroundImage2File($backgroundImage2File)
    {
        $this->backgroundImage2File = $backgroundImage2File;

        return $this;
    }
    
    /**
     * Get previewImage2
     *
     * @return Template
     */
    public function getPreviewImage2()
    {
        return $this->previewImage2;
    }
    
    /**
     * @param string $previewImage2
     *
     * @return Template
     */
    public function setPreviewImage2($previewImage2)
    {
        $this->previewImage2 = $previewImage2;

        return $this;
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
    
    /**
     * @return string
     */
    public function getFrontPageHtml()
    {
        return $this->frontPageHtml;
    }

    /**
     * @param string $frontPageHtml
     */
    public function setFrontPageHtml($frontPageHtml)
    {
        $this->frontPageHtml = $frontPageHtml;
    }
    
    /**
     * @return string
     */
    public function getBackPageHtml()
    {
        return $this->backPageHtml;
    }

    /**
     * @param string $backPageHtml
     */
    public function setBackPageHtml($backPageHtml)
    {
        $this->backPageHtml = $backPageHtml;
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
    
    /**
     * Set pdfPath
     *
     * @param string $pdfPath
     * @return Template
     */
    public function setPdfPath($pdfPath)
    {
        $this->pdfPath = $pdfPath;

        return $this;
    }

    /**
     * Get pdfPath
     *
     * @return string 
     */
    public function getPdfPath()
    {
        return $this->pdfPath;
    }
    
            
    /**
     * Set print
     *
     * @param boolean $print
     * @return Template
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
}

