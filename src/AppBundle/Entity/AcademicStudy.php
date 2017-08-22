<?php


namespace AppBundle\Entity;

use AppBundle\Services\Formatting;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Validator\Constraints as Assert;


/**
 * AcademicStudy
 *
 * @ORM\Table(name="academic_study")
 * @ORM\Entity(repositoryClass="AppBundle\Repository\AcademicStudyRepository")
 */
class AcademicStudy extends Timestampable
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
	 * @ORM\ManyToMany(targetEntity="AppBundle\Entity\Registration", mappedBy="academicStudies")
	 */
	private $registrations;

	public function __construct()
	{
		parent::__construct();
		$this->registrations = new ArrayCollection();
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
	 * @param mixed $registrations
	 */
	public function setRegistrations($registrations)
	{
		$this->registrations = $registrations;
	}

	public function addRegistration($registration)
	{
		if($this->registrations->contains($registration)){
			return;
		}

		$this->registrations->add($registration);
	}

	public function removeRegistration($registration)
	{
		if(!$this->registrations->contains($registration)){
			return;
		}

		$this->registrations->remove($registration);
	}

	public function __toString()
	{
		return $this->name;
	}
}