<?php


namespace AppBundle\Entity\Registration;

use AppBundle\Entity\City;
use AppBundle\Entity\Registration;
use AppBundle\Entity\Timestampable;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Validator\Constraints as Assert;

/**
 * PlaceResidence
 *
 * @ORM\Table(name="place_residence")
 * @ORM\Entity(repositoryClass="AppBundle\Repository\Registration\PlaceResidenceRepository")
 */
class PlaceResidence extends Timestampable
{
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
	 * @ORM\Column(name="address", type="string", length=255)
	 */
	private $address;
    
    /**
	 * @var string
	 *
	 * @ORM\Column(name="address_info", type="string", length=255, nullable=true)
	 */
	private $addressInfo;

	/**
	 * @var string
	 *
	 * @ORM\Column(name="postal_code", type="string", length=5)
	 */
	private $postalCode;


	/**
	 * @ORM\ManyToOne(targetEntity="AppBundle\Entity\City", inversedBy="places", cascade={"persist"})
	 * @ORM\JoinColumn(name="city_id", referencedColumnName="id", onDelete="CASCADE")
	 */
	private $city;

	/**
	 * @ORM\OneToOne(targetEntity="AppBundle\Entity\Registration", mappedBy="placeResidence")
	 */
	private $registration;

	public function __construct()
	{
		parent::__construct();
	}

	/**
	 * @return int
	 */
	public function getId()
	{
		return $this->id;
	}

	/**
	 * @return string
	 */
	public function getAddress()
	{
		return $this->address;
	}

	/**
	 * @param string $address
	 */
	public function setAddress($address)
	{
		$this->address = $address;
	}
    
    /**
	 * @return string
	 */
	public function getAddressInfo()
	{
		return $this->addressInfo;
	}

	/**
	 * @param string $addressInfo
	 */
	public function setAddressInfo($addressInfo)
	{
		$this->addressInfo = $addressInfo;
	}

	/**
	 * @return string
	 */
	public function getPostalCode()
	{
		return $this->postalCode;
	}

	/**
	 * @param string $postalCode
	 */
	public function setPostalCode($postalCode)
	{
		$this->postalCode = $postalCode;
	}

	/**
	 * @return Registration
	 */
	public function getRegistration()
	{
		return $this->registration;
	}

	/**
	 * @param Registration $registration
	 */
	public function setRegistration($registration)
	{
		$this->registration = $registration;
	}

	/**
	 * @return City
	 */
	public function getCity()
	{
		return $this->city;
	}

	/**
	 * @param City $city
	 */
	public function setCity($city)
	{
		$this->city = $city;
	}

	public function __toString()
	{
		return $this->getAddress();
	}
}
