<?php


namespace AppBundle\Entity;

use AppBundle\Entity\Registration\Speciality;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Validator\Constraints as Assert;

/**
 * RegistrationHasSpeciality
 *
 * @ORM\Table(name="registration_has_speciality")
 * @ORM\Entity(repositoryClass="AppBundle\Repository\RegistrationHasSpecialityRepository")
 */
class RegistrationHasSpeciality extends Timestampable
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
	 * @ORM\Column(name="value", type="string", length=255)
	 */
	private $value;

	/**
	 * @ORM\ManyToOne(targetEntity="AppBundle\Entity\Registration", inversedBy="registrationsHaveSpecialities", cascade={"persist"})
	 * @ORM\JoinColumn(name="registration_id", referencedColumnName="id", onDelete="CASCADE")
	 */
	private $registration;

	/**
	 * @ORM\ManyToOne(targetEntity="AppBundle\Entity\Registration\Speciality", inversedBy="registrationsHaveSpecialities", cascade={"persist"})
	 * @ORM\JoinColumn(name="speciality_id", referencedColumnName="id", onDelete="CASCADE")
	 */
	private $speciality;

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
	public function getValue()
	{
		return $this->value;
	}

	/**
	 * @param string $value
	 */
	public function setValue($value)
	{
		$this->value = $value;
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
	 * @return Speciality
	 */
	public function getSpeciality()
	{
		return $this->speciality;
	}

	/**
	 * @param Speciality $speciality
	 */
	public function setSpeciality($speciality)
	{
		$this->speciality = $speciality;
	}

	public function __toString()
	{
		return $this->value;
	}
}