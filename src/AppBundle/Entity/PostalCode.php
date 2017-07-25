<?php

namespace AppBundle\Entity;

use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\ORM\Mapping as ORM;

/**
 * PostalCode
 *
 * @ORM\Table(name="postal_code")
 * @ORM\Entity(repositoryClass="AppBundle\Repository\PostalCodeRepository")
 */
class PostalCode extends Timestampable
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
     * @ORM\Column(name="cp", type="string", length=5)
     */
    private $cp;

    /**
     * @var string
     *
     * @ORM\Column(name="country", type="string", length=3)
     */
    private $country;

    /**
     * @var string
     *
     * @ORM\Column(name="place", type="string", length=48)
     */
    private $place;

    /**
     * @var string
     *
     * @ORM\Column(name="state", type="string", length=48)
     */
    private $state;

    /**
     * @var string
     *
     * @ORM\Column(name="city", type="string", length=48)
     */
    private $city;

    /**
     * @var string
     *
     * @ORM\Column(name="city_iso", type="string", length=3)
     */
    private $cityIso;

    /**
     * @var string
     *
     * @ORM\Column(name="latitude", type="decimal", precision=13, scale=9)
     */
    private $latitude;

    /**
     * @var string
     *
     * @ORM\Column(name="longitude", type="decimal", precision=13, scale=9)
     */
    private $longitude;

    /**
     * @var ArrayCollection
     *
     * @ORM\OneToMany(targetEntity="Client", mappedBy="postalCode", cascade={"persist"})
     */
    private $clients;

    public function __construct()
    {
        parent::__construct();
        $this->clients = new ArrayCollection();
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
     * Set cp
     *
     * @param string $cp
     * @return PostalCode
     */
    public function setCp($cp)
    {
        $this->cp = $cp;

        return $this;
    }

    /**
     * Get cp
     *
     * @return string 
     */
    public function getCp()
    {
        return $this->cp;
    }

    /**
     * Set country
     *
     * @param string $country
     * @return PostalCode
     */
    public function setCountry($country)
    {
        $this->country = $country;

        return $this;
    }

    /**
     * Get country
     *
     * @return string 
     */
    public function getCountry()
    {
        return $this->country;
    }

    /**
     * Set place
     *
     * @param string $place
     * @return PostalCode
     */
    public function setPlace($place)
    {
        $this->place = $place;

        return $this;
    }

    /**
     * Get place
     *
     * @return string 
     */
    public function getPlace()
    {
        return $this->place;
    }

    /**
     * Set state
     *
     * @param string $state
     * @return PostalCode
     */
    public function setState($state)
    {
        $this->state = $state;

        return $this;
    }

    /**
     * Get state
     *
     * @return string 
     */
    public function getState()
    {
        return $this->state;
    }

    /**
     * Set city
     *
     * @param string $city
     * @return PostalCode
     */
    public function setCity($city)
    {
        $this->city = $city;

        return $this;
    }

    /**
     * Get city
     *
     * @return string 
     */
    public function getCity()
    {
        return $this->city;
    }

    /**
     * Set cityIso
     *
     * @param string $cityIso
     * @return PostalCode
     */
    public function setCityIso($cityIso)
    {
        $this->cityIso = $cityIso;

        return $this;
    }

    /**
     * Get cityIso
     *
     * @return string 
     */
    public function getCityIso()
    {
        return $this->cityIso;
    }

    /**
     * Set latitude
     *
     * @param string $latitude
     * @return PostalCode
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
     * @return PostalCode
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
     * @return ArrayCollection
     */
    public function getClients()
    {
        return $this->clients;
    }

    /**
     * @param ArrayCollection $clients
     */
    public function setClients($clients)
    {
        $this->clients = $clients;
    }

    /**
     * @param Client $client
     */
    public function addClient(Client $client)
    {
        if ($this->clients->contains($client)){
            return;
        }

        $this->clients->add($client);
    }

    /**
     * @param Client $client
     */
    public function removeClient(Client $client)
    {
        if (!$this->clients->contains($client)){
            return;
        }

        $this->clients->remove($client);
    }
}
