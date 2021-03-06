<?php

namespace AppBundle\Entity;

use AppBundle\Services\ClientCodeGenerator;
use AppBundle\Services\GoogleMapsApi;
use AppBundle\Services\ImageHandler;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\HttpFoundation\File\UploadedFile;
use Symfony\Component\Security\Core\User\UserInterface;
use Symfony\Component\Validator\Constraints as Assert;
use EditorBundle\Entity\Template;

/**
 * Client
 *
 * @ORM\Table(name="client")
 * @ORM\Entity(repositoryClass="AppBundle\Repository\ClientRepository")
 */
class Client extends Timestampable implements UserInterface
{
    const ROLE_ADMIN = "ROLE_ADMIN";
    const ROLE_CLIENT = "ROLE_CLIENT";

    /**
     * @var int
     *
     * @ORM\Column(name="id", type="integer")
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="AUTO")
     */
    protected $id;

    /**
     * @var string
     *
     * @ORM\Column(name="code", type="string", length=255, nullable=true)
     */
    protected $code;

    /**
     * @var string
     *
     * @ORM\Column(name="username", type="string", length=255, unique=true)
     */
    protected $username;

    /**
     * @var string
     *
     * @ORM\Column(name="password", type="string", length=255)
     */
    protected $password;

    /**
     * @var string
     *
     * @ORM\Column(name="salt", type="string", length=255)
     */
    protected $salt;

    /**
     * @var boolean
     *
     * @ORM\Column(name="active", type="boolean")
     */
    protected $active;

    /**
     * @var boolean
     *
     * @ORM\Column(name="is_admin", type="boolean")
     */
    protected $isAdministrator;

    /**
     * @var string
     *
     * @ORM\Column(name="role", type="string", length=255)
     */
    protected $role;

    /**
     * @var string
     *
     * @ORM\Column(name="trade_name", type="string", length=255)
     */
    private $tradeName;

    /**
     * @var string
     *
     * @ORM\Column(name="tag_line", type="string", length=255, nullable=true)
     */
    private $tagLine;

    /**
     * @var string
     *
     * @ORM\Column(name="short_description", type="text", nullable=true)
     */
    private $shortDescription;

    /**
     * @var string
     *
     * @ORM\Column(name="description", type="text", nullable=true)
     */
    private $description;

    /**
     * @var string
     *
     * @ORM\Column(name="technology", type="text", nullable=true)
     */
    private $technology;

    /**
     * @var string
     *
     * @ORM\Column(name="society_name", type="text")
     */
    private $societyName;

    /**
     * @var string
     *
     * @ORM\Column(name="social_number", type="string", length=255, nullable=true)
     */
    private $socialNumber;

    /**
     * @var string
     *
     * @ORM\Column(name="nif", type="string", length=255)
     */
    private $nif;

    /**
     * @var string
     *
     * @ORM\Column(name="url_web", type="string", length=255, nullable=true)
     */
    private $urlWeb;

    /**
     * @var string
     *
     * @ORM\Column(name="facebook", type="string", length=255, nullable=true)
     */
    private $facebook;

    /**
     * @var string
     *
     * @ORM\Column(name="instagram", type="string", length=255, nullable=true)
     */
    private $instagram;

    /**
     * @var string
     *
     * @ORM\Column(name="blog", type="string", length=255, nullable=true)
     */
    private $blog;

    /**
     * @ORM\ManyToOne(targetEntity="Plan", inversedBy="clients", cascade={"persist"})
     * @ORM\JoinColumn(name="plan_id", referencedColumnName="id", onDelete="CASCADE")
     */
    private $plan;

    /**
     * @ORM\OneToOne(targetEntity="Address", inversedBy="client", cascade={"persist"})
     * @ORM\JoinColumn(name="billing_address_id", referencedColumnName="id")
     */
    private $billingAddress;

    /**
     * @ORM\OneToOne(targetEntity="LocalAddress", inversedBy="client", cascade={"persist"})
     * @ORM\JoinColumn(name="local_address_id", referencedColumnName="id", nullable=true)
     */
    private $localAddress;

    /**
     * @var string
     *
     * @ORM\Column(name="thumbnail_logo", type="string", length=255, nullable=true)
     */
    private $thumbnailLogo;

    /**
     * @var string
     *
     * @ORM\Column(name="logo", type="string", length=255)
     */
    private $logo;

    /**
     * @Assert\File(
     *     mimeTypes = "image/*",
     *     mimeTypesMessage = "Please upload a valid image"
     * )
     * @Assert\File(
     *     maxSize = "5M",
     *	   maxSizeMessage = "Please upload an image with max size"
     * )
     */
    private $logoFile;

    /**
     * @var ArrayCollection
     *
     * @ORM\OneToMany(targetEntity="FileDoc", mappedBy="client", cascade={"persist"})
     */
    private $fileDocs;

    /**
     * @var ArrayCollection
     *
     * @ORM\OneToMany(targetEntity="PressRelease", mappedBy="client", cascade={"persist"})
     */
    private $pressReleases;

    /**
     * @var ArrayCollection
     *
     * @ORM\OneToMany(targetEntity="CreativityOrder", mappedBy="client", cascade={"persist"})
     */
    private $creativityOrders;

     /**
     * @var ArrayCollection
     *
     * @ORM\OneToMany(targetEntity="EditorBundle\Entity\Template", mappedBy="client", cascade={"remove"})
     */
    private $templates;
    
    /**
     * @var string
     *
     * @ORM\Column(name="cabinas", type="string", length=255, nullable=true)
     */
    private $cabinas;

    /**
     * @var string
     *
     * @ORM\Column(name="profesionales_cabinas", type="string", length=255, nullable=true)
     */
    private $profesionalesCabinas;

    /**
     * @var boolean
     *
     * @ORM\Column(name="recepcionista", type="boolean", nullable=true)
     */
    private $recepcionista;


    public function __construct()
    {
        parent::__construct();
        $this->salt = md5(time());
        $this->active = true;
        $this->isAdministrator = false;
        $this->role = self::ROLE_CLIENT;
        $this->fileDocs = new ArrayCollection();
        $this->pressReleases = new ArrayCollection();
        $this->creativityOrders = new ArrayCollection();
        $this->templates = new ArrayCollection();
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
    public function getCode()
    {
        return $this->code;
    }

    /**
     * @param $code
     */
    public function setCode($code)
    {
        $this->code = $code;
    }

    /**
     * @return string
     */
    public function getUsername()
    {
        return $this->username;
    }

    /**
     * @param string $username
     */
    public function setUsername($username)
    {
        $this->username = $username;
    }

    /**
     * @return string
     */
    public function getPassword()
    {
        return $this->password;
    }

    /**
     * @param string $password
     */
    public function setPassword($password)
    {
        $this->password = $password;
    }

    /**
     * @return boolean
     */
    public function isActive()
    {
        return $this->active;
    }

    /**
     * @param boolean $active
     */
    public function setActive($active)
    {
        $this->active = $active;
    }

    /**
     * @return boolean
     */
    public function isIsAdministrator()
    {
        return $this->isAdministrator;
    }

    /**
     * @param boolean $isAdministrator
     */
    public function setIsAdministrator($isAdministrator)
    {
        $this->isAdministrator = $isAdministrator;
    }

    /**
     * @param string $role
     */
    public function setRole($role)
    {
        $this->role = $role;
    }

    /**
     * @return array
     */
    public function getRoles()
    {
        return explode(',', $this->role);
    }

    /**
     * @param string $role
     */
    public function setRoles($role)
    {
        $this->role = $role;
    }

    public function addRole($role)
    {
        $this->role .= ",".$role;
    }

    public function hasRole($role)
    {
        if(in_array($role, $this->getRoles())){
            return true;
        }
        return false;
    }

    /**
     * Returns the salt that was originally used to encode the password.
     *
     * This can return null if the password was not encoded using a salt.
     *
     * @return string|null The salt
     */
    public function getSalt()
    {
        return $this->salt;
    }

    /**
     * @param string $salt
     */
    public function setSalt($salt)
    {
        $this->salt = $salt;
    }

    /**
     * Removes sensitive data from the user.
     *
     * This is important if, at any given point, sensitive information like
     * the plain-text password is stored on this object.
     */
    public function eraseCredentials()
    {
        //Nothing
    }

    /**
     * Set tradeName
     *
     * @param string $tradeName
     * @return Client
     */
    public function setTradeName($tradeName)
    {
        $this->tradeName = $tradeName;

        return $this;
    }

    /**
     * Get tradeName
     *
     * @return string 
     */
    public function getTradeName()
    {
        return $this->tradeName;
    }

    /**
     * Set tagLine
     *
     * @param string $tagLine
     * @return Client
     */
    public function setTagLine($tagLine)
    {
        $this->tagLine = $tagLine;

        return $this;
    }

    /**
     * Get tagLine
     *
     * @return string 
     */
    public function getTagLine()
    {
        return $this->tagLine;
    }

    /**
     * Set description
     *
     * @param string $description
     * @return Client
     */
    public function setDescription($description)
    {
        $this->description = $description;

        return $this;
    }

    /**
     * Get description
     *
     * @return string 
     */
    public function getDescription()
    {
        return $this->description;
    }

    /**
     * Set technology
     *
     * @param string $technology
     * @return Client
     */
    public function setTechnology($technology)
    {
        $this->technology = $technology;

        return $this;
    }

    /**
     * Get technology
     *
     * @return string 
     */
    public function getTechnology()
    {
        return $this->technology;
    }

    /**
     * Set shortDescription
     *
     * @param string $shortDescription
     * @return Client
     */
    public function setShortDescription($shortDescription)
    {
        $this->shortDescription = $shortDescription;

        return $this;
    }

    /**
     * Get shortDescription
     *
     * @return string 
     */
    public function getShortDescription()
    {
        return $this->shortDescription;
    }

    /**
     * Set societyName
     *
     * @param string $societyName
     * @return Client
     */
    public function setSocietyName($societyName)
    {
        $this->societyName = $societyName;

        return $this;
    }

    /**
     * Get societyName
     *
     * @return string 
     */
    public function getSocietyName()
    {
        return $this->societyName;
    }

    /**
     * Set socialNumber
     *
     * @param string $socialNumber
     * @return Client
     */
    public function setSocialNumber($socialNumber)
    {
        $this->socialNumber = $socialNumber;

        return $this;
    }

    /**
     * Get socialNumber
     *
     * @return string 
     */
    public function getSocialNumber()
    {
        return $this->socialNumber;
    }

    /**
     * Set nif
     *
     * @param string $nif
     * @return Client
     */
    public function setNif($nif)
    {
        $this->nif = $nif;

        return $this;
    }

    /**
     * Get nif
     *
     * @return string 
     */
    public function getNif()
    {
        return $this->nif;
    }

    /**
     * @return mixed
     */
    public function getUrlWeb()
    {
        return $this->urlWeb;
    }

    /**
     * @param mixed $urlWeb
     */
    public function setUrlWeb($urlWeb)
    {
        $this->urlWeb = $urlWeb;
    }

    /**
     * @return string
     */
    public function getFacebook()
    {
        return $this->facebook;
    }

    /**
     * @param string $facebook
     */
    public function setFacebook($facebook)
    {
        $this->facebook = $facebook;
    }

    /**
     * @return string
     */
    public function getInstagram()
    {
        return $this->instagram;
    }

    /**
     * @param string $instagram
     */
    public function setInstagram($instagram)
    {
        $this->instagram = $instagram;
    }

    /**
     * @return mixed
     */
    public function getBlog()
    {
        return $this->blog;
    }

    /**
     * @param mixed $blog
     */
    public function setBlog($blog)
    {
        $this->blog = $blog;
    }

    /**
     * @return Plan
     */
    public function getPlan()
    {
        return $this->plan;
    }

    /**
     * @param Plan $plan
     */
    public function setPlan($plan)
    {
        $this->plan = $plan;
    }

    /**
     * @return Address
     */
    public function getBillingAddress()
    {
        return $this->billingAddress;
    }

    /**
     * @param Address $billingAddress
     */
    public function setBillingAddress($billingAddress)
    {
        $billingAddress->setClient($this);
        $this->billingAddress = $billingAddress;
    }

    /**
     * @return LocalAddress
     */
    public function getLocalAddress()
    {
        return $this->localAddress;
    }

    /**
     * @param LocalAddress $localAddress
     */
    public function setLocalAddress($localAddress)
    {
        $this->localAddress = $localAddress;
    }

    public function __toString()
    {
        return $this->username;
    }

    static public function generateRandomString($length = 10) {
        return substr(str_shuffle("0123456789abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ"), 0, $length);
    }

    /**
     * @return string
     */
    public function getLogo()
    {
        return $this->logo;
    }

    /**
     * @param string $logo
     */
    public function setLogo($logo)
    {
        $this->logo = $logo;
    }

    /**
     * @return UploadedFile
     */
    public function getLogoFile()
    {
        return $this->logoFile;
    }

    /**
     * @param UploadedFile $logoFile
     */
    public function setLogoFile(UploadedFile $logoFile = null)
    {
        $this->logoFile = $logoFile;
    }

    public function uploadLogo($directory)
    {
        if (null === $this->getLogoFile()) {
            return;
        }

        $fileName = uniqid('logo-').'.'.$this->getLogoFile()->guessExtension();

        $this->getLogoFile()->move($directory, $fileName);
        //
        $fileFullPath = $directory . '/' . $fileName;
        $pathArray = explode('/', $fileFullPath);
        $pathArraySize = count($pathArray);
        $this->setThumbnailLogo('thumbnail_' . $pathArray[$pathArraySize - 1]);
        $pathArray[$pathArraySize - 1] = $this->getThumbnailLogo();
        $thumbnailFullPath = implode('/', $pathArray);
        $logoThumbnail = ImageHandler::generateImageThumbnail($fileFullPath, $thumbnailFullPath, 90, 90);
        //

        $this->setLogo($fileName);

        unset($this->logoFile);
        unset($logoThumbnail);
    }

    /**
     * @return string
     */
    public function getThumbnailLogo()
    {
        return $this->thumbnailLogo;
    }

    /**
     * @param string $thumbnailLogo
     */
    public function setThumbnailLogo($thumbnailLogo)
    {
        $this->thumbnailLogo = $thumbnailLogo;
    }

    /**
     * @return ArrayCollection
     */
    public function getFileDocs()
    {
        return $this->fileDocs;
    }

    /**
     * @param ArrayCollection $fileDocs
     */
    public function setFileDocs($fileDocs)
    {
        $this->fileDocs = $fileDocs;
    }

    /**
     * @param FileDoc $fileDoc
     */
    public function addFileDoc(FileDoc $fileDoc)
    {
        if ($this->fileDocs->contains($fileDoc)){
            return;
        }

        $this->fileDocs->add($fileDoc);
        $fileDoc->setClient($this);
    }

    /**
     * @param FileDoc $fileDoc
     */
    public function removeFileDoc(FileDoc $fileDoc)
    {
        if (!$this->fileDocs->contains($fileDoc)){
            return;
        }

        $this->fileDocs->remove($fileDoc);
    }

    /**
     * @return ArrayCollection
     */
    public function getPressReleases()
    {
        return $this->pressReleases;
    }

    /**
     * @param ArrayCollection $pressReleases
     */
    public function setPressReleases($pressReleases)
    {
        $this->pressReleases = $pressReleases;
    }

    /**
     * @param PressRelease $pressRelease
     */
    public function addPressRelease(PressRelease $pressRelease)
    {
        if ($this->pressReleases->contains($pressRelease)){
            return;
        }

        $this->pressReleases->add($pressRelease);
        $pressRelease->setClient($this);
    }

    /**
     * @param PressRelease $pressRelease
     */
    public function removePressRelease(PressRelease $pressRelease)
    {
        if (!$this->pressReleases->contains($pressRelease)){
            return;
        }

        $this->pressReleases->remove($pressRelease);
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
     * @param CreativityOrder $pressRelease
     */
    public function addCreativityOrder(CreativityOrder $pressRelease)
    {
        if ($this->pressReleases->contains($pressRelease)){
            return;
        }

        $this->pressReleases->add($pressRelease);
        $pressRelease->setClient($this);
    }

    /**
     * @param CreativityOrder $pressRelease
     */
    public function removeCreativityOrder(CreativityOrder $pressRelease)
    {
        if (!$this->pressReleases->contains($pressRelease)){
            return;
        }

        $this->pressReleases->remove($pressRelease);
    }

    public function getCompleteAddress()
    {
        return $this->getBillingAddress()->getAddress() . ' ' . $this->getBillingAddress()->getPostalCode() . ' ' . $this->getBillingAddress()->getCity() . ' ' . $this->getBillingAddress()->getCity()->getProvince() . ' ' . $this->getBillingAddress()->getCity()->getProvince()->getCountry();
    }

    public function getLatitude()
    {
        $googleLocation = GoogleMapsApi::getGoogleApiLocation($this->getCompleteAddress());

        return $googleLocation['latitude'];
    }

    public function getLongitude()
    {
        $googleLocation = GoogleMapsApi::getGoogleApiLocation($this->getCompleteAddress());

        return $googleLocation['longitude'];
    }
    
     /**
     * Add template
     *
     * @param Template $template
     *
     * @return Template
     */
    public function addChild(Template $template)
    {
        $this->templates->add($template);

        return $this;
    }

    /**
     * Remove template
     *
     * @param Template $template
     */
    public function removeChild(Template $template)
    {
        $this->templates->removeElement($template);
    }

    /**
     * Get templates
     *
     * @return ArrayCollection
     */
    public function getChilds()
    {
        return $this->templates;
    }
    
    public function isGranted($role)
    {
        return in_array($role, $this->getRoles());
    }
    
    
        /**
	 * @return string
	 */
	public function getCabinas()
	{
		return $this->cabinas;
	}

	/**
	 * @param string $cabinas
	 */
	public function setCabinas($cabinas)
	{
		$this->cabinas = $cabinas;
	}
        
        /**
	 * @return string
	 */
	public function getProfesionalesCabinas()
	{
		return $this->profesionalesCabinas;
	}

	/**
	 * @param string $profesionalesCabinas
	 */
	public function setProfesionalesCabinas($profesionalesCabinas)
	{
		$this->profesionalesCabinas = $profesionalesCabinas;
	}
        
        /**
	 * @return string
	 */
	public function getRecepcionista()
	{
		return $this->recepcionista;
	}

	/**
	 * @param string $recepcionista
	 */
	public function setRecepcionista($recepcionista)
	{
		$this->recepcionista = $recepcionista;
	}
        
}
