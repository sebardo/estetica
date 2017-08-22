<?php


namespace AppBundle\Entity\Registration;

use AppBundle\Entity\Timestampable;
use AppBundle\Services\Formatting;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Validator\Constraints as Assert;

/**
 * Course
 *
 * @ORM\Table(name="course")
 * @ORM\Entity(repositoryClass="AppBundle\Repository\Registration\CourseRepository")
 */
class Course extends Timestampable
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
	 * @var ArrayCollection
	 *
	 * @ORM\OneToMany(targetEntity="AppBundle\Entity\RegistrationHasCourse", mappedBy="course", cascade={"persist"})
	 */
	private $registrationsHaveCourses;

	public function __construct()
	{
		parent::__construct();
		$this->registrationsHaveCourses = new ArrayCollection();
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
	 * @return mixed
	 */
	public function getRegistrationsHaveCourses()
	{
		return $this->registrationsHaveCourses;
	}

	/**
	 * @param mixed $registrationsHaveCourses
	 */
	public function setRegistrationsHaveCourses($registrationsHaveCourses)
	{
		$this->registrationsHaveCourses = $registrationsHaveCourses;
	}

	public function addRegistrationHasCourse($registrationHasCourse)
	{
		if($this->registrationsHaveCourses->contains($registrationHasCourse)){
			return;
		}

		$this->registrationsHaveCourses->add($registrationHasCourse);
	}

	public function removeRegistrationHasCourse($registrationHasCourse)
	{
		if(!$this->registrationsHaveCourses->contains($registrationHasCourse)){
			return;
		}

		$this->registrationsHaveCourses->remove($registrationHasCourse);
	}

	public function __toString()
	{
		return $this->name;
	}
}
