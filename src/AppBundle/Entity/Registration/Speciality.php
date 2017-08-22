<?php


namespace AppBundle\Entity\Registration;

use AppBundle\Entity\Timestampable;
use AppBundle\Services\Formatting;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Validator\Constraints as Assert;


/**
 * Speciality
 *
 * @ORM\Table(name="speciality")
 * @ORM\Entity(repositoryClass="AppBundle\Repository\Registration\SpecialityRepository")
 */
class Speciality extends Timestampable
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
	 * @ORM\Column(name="name", type="string", length=255, unique=true)
	 */
	private $name;

	/**
	 * @var string
	 *
	 * @ORM\Column(name="format_name", type="string", length=255, unique=true, nullable=true)
	 */
	private $formatName;

	/**
	 * @ORM\ManyToOne(targetEntity="ParentSpeciality", inversedBy="specialities", cascade={"persist"})
	 * @ORM\JoinColumn(name="parent_id", referencedColumnName="id", onDelete="CASCADE")
	 */
	private $parent;

	/**
	 * @var ArrayCollection
	 *
	 * @ORM\OneToMany(targetEntity="AppBundle\Entity\RegistrationHasSpeciality", mappedBy="speciality", cascade={"persist"})
	 */
	private $registrationsHaveSpecialities;

	public function __construct()
	{
		parent::__construct();
		$this->registrationsHaveSpecialities = new ArrayCollection();
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
		$this->setFormatName($name);
	}

	/**
	 * @return string
	 */
	public function getFormatName()
	{
		return $this->formatName;
	}

	/**
	 * @param string $name
	 */
	public function setFormatName($name)
	{
		$this->formatName = Formatting::formatString($name);
	}

	/**
	 * @return ParentSpeciality
	 */
	public function getParent()
	{
		return $this->parent;
	}

	/**
	 * @param ParentSpeciality $parent
	 */
	public function setParent($parent)
	{
		$this->parent = $parent;
	}

	/**
	 * @return mixed
	 */
	public function getRegistrationsHaveSpecialities()
	{
		return $this->registrationsHaveSpecialities;
	}

	/**
	 * @param mixed $registrationsHaveSpecialities
	 */
	public function setRegistrationsHaveSpecialities($registrationsHaveSpecialities)
	{
		$this->registrationsHaveSpecialities = $registrationsHaveSpecialities;
	}

	public function addRegistrationHasSpeciality($registrationHasSpeciality)
	{
		if($this->registrationsHaveSpecialities->contains($registrationHasSpeciality)){
			return;
		}

		$this->registrationsHaveSpecialities->add($registrationHasSpeciality);
	}

	public function removeRegistrationHasSpeciality($registrationHasSpeciality)
	{
		if(!$this->registrationsHaveSpecialities->contains($registrationHasSpeciality)){
			return;
		}

		$this->registrationsHaveSpecialities->remove($registrationHasSpeciality);
	}

	public function __toString()
	{
		return $this->name;
	}
}