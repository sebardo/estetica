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
    const CREATED_STATE = 'Created';
    const PENDING_STATE = 'Pending';
    const APPROVED_STATE = 'Approved';
    const CANCELED_STATE = 'Canceled';
    
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
     */
    private $backgroundImageFile;
    
    /**
     * @var string
     *
     * @ORM\Column(name="backgroundImage", type="string", length=255, nullable=true)
     */
    private $backgroundImage;

    /**
     * @var File
     *
     * @Vich\UploadableField(mapping="templates", fileNameProperty="backgroundImage2")
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
     * Constructor
     */
    public function __construct()
    {
        $this->childs = new ArrayCollection();
        $this->setStatus(self::CREATED_STATE);
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
    
}
