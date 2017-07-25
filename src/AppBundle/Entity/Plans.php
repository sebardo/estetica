<?php

namespace AppBundle\Entity;

use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\ORM\Mapping as ORM;

/**
 * Plans
 *
 * @ORM\Table(name="plan")
 * @ORM\Entity(repositoryClass="AppBundle\Repository\PlanRepository")
 */
class Plan extends Timestampable
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
     * @ORM\Column(name="name", type="string", length=255)
     */
    private $name;

    /**
     * @var string
     *
     * @ORM\Column(name="description", type="text")
     */
    private $description;

    /**
     * @var float
     *
     * @ORM\Column(name="monthly_prize", type="float")
     */
    private $monthlyPrize;

    /**
     * @var string
     *
     * @ORM\Column(name="other_prize", type="string", length=255)
     */
    private $otherPrize;

    /**
     * @var ArrayCollection
     *
     * @ORM\OneToMany(targetEntity="Client", mappedBy="plan", cascade={"persist"})
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
     * Set name
     *
     * @param string $name
     * @return Plans
     */
    public function setName($name)
    {
        $this->name = $name;

        return $this;
    }

    /**
     * Get name
     *
     * @return string 
     */
    public function getName()
    {
        return $this->name;
    }

    /**
     * Set description
     *
     * @param string $description
     * @return Plans
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
     * Set monthlyPrize
     *
     * @param float $monthlyPrize
     * @return Plans
     */
    public function setMonthlyPrize($monthlyPrize)
    {
        $this->monthlyPrize = $monthlyPrize;

        return $this;
    }

    /**
     * Get monthlyPrize
     *
     * @return float 
     */
    public function getMonthlyPrize()
    {
        return $this->monthlyPrize;
    }

    /**
     * Set otherPrize
     *
     * @param string $otherPrize
     * @return Plans
     */
    public function setOtherPrize($otherPrize)
    {
        $this->otherPrize = $otherPrize;

        return $this;
    }

    /**
     * Get otherPrize
     *
     * @return string 
     */
    public function getOtherPrize()
    {
        return $this->otherPrize;
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

    public function __toString()
    {
        return $this->name;
    }
}
