<?php

namespace AppBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * Address
 *
 * @ORM\Table(name="address")
 * @ORM\Entity(repositoryClass="AppBundle\Repository\AddressRepository")
 */
class Address extends Timestampable
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
     * @ORM\Column(name="address", type="string", length=255)
     */
    private $address;

    /**
     * @var string
     *
     * @ORM\Column(name="phone", type="string", length=255)
     */
    private $phone;

    /**
     * @var string
     *
     * @ORM\Column(name="email", type="string", length=255)
     */
    private $email;

    /**
     * @var string
     *
     * @ORM\Column(name="contact", type="string", length=255)
     */
    private $contact;

    /**
     * @var boolean
     *
     * @ORM\Column(name="is_billing", type="boolean")
     */
    private $isBilling;

    /**
     * @var string
     *
     * @ORM\Column(name="postal_code", type="string", length=5)
     */
    private $postalCode;


    /**
     * @ORM\ManyToOne(targetEntity="City", inversedBy="addresses", cascade={"persist"})
     * @ORM\JoinColumn(name="city_id", referencedColumnName="id", onDelete="CASCADE")
     */
    private $city;

    public function __construct()
    {
        parent::__construct();
        $this->isBilling = false;
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
     * Set address
     *
     * @param string $address
     * @return Address
     */
    public function setAddress($address)
    {
        $this->address = $address;

        return $this;
    }

    /**
     * Get address
     *
     * @return string 
     */
    public function getAddress()
    {
        return $this->address;
    }

    /**
     * Set phone
     *
     * @param string $phone
     * @return Address
     */
    public function setPhone($phone)
    {
        $this->phone = $phone;

        return $this;
    }

    /**
     * Get phone
     *
     * @return string 
     */
    public function getPhone()
    {
        return $this->phone;
    }

    /**
     * Set email
     *
     * @param string $email
     * @return Address
     */
    public function setEmail($email)
    {
        $this->email = $email;

        return $this;
    }

    /**
     * Get email
     *
     * @return string 
     */
    public function getEmail()
    {
        return $this->email;
    }

    /**
     * Set contact
     *
     * @param string $contact
     * @return Address
     */
    public function setContact($contact)
    {
        $this->contact = $contact;

        return $this;
    }

    /**
     * Get contact
     *
     * @return string 
     */
    public function getContact()
    {
        return $this->contact;
    }

//    /**
//     * @return PostalCode
//     */
//    public function getPostalCode()
//    {
//        return $this->postalCode;
//    }
//
//    /**
//     * @param PostalCode $postalCode
//     */
//    public function setPostalCode($postalCode)
//    {
//        $this->postalCode = $postalCode;
//    }

    /**
     * @return boolean
     */
    public function isIsBilling()
    {
        return $this->isBilling;
    }

    /**
     * @param boolean $isBilling
     */
    public function setIsBilling($isBilling)
    {
        $this->isBilling = $isBilling;
    }

    /**
     * @return mixed
     */
    public function getPostalCode()
    {
        return $this->postalCode;
    }

    /**
     * @param mixed $postalCode
     */
    public function setPostalCode($postalCode)
    {
        $this->postalCode = $postalCode;
    }

    /**
     * @return mixed
     */
    public function getCity()
    {
        return $this->city;
    }

    /**
     * @param mixed $city
     */
    public function setCity($city)
    {
        $this->city = $city;
    }

//    /**
//     * @return Client
//     */
//    public function getClient()
//    {
//        return $this->client;
//    }
//
//    /**
//     * @param Client $client
//     */
//    public function setClient($client)
//    {
//        $this->client = $client;
//    }
}
