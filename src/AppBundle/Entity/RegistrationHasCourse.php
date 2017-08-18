<?php


namespace AppBundle\Entity;

use AppBundle\Entity\Registration\Course;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Validator\Constraints as Assert;

/**
 * RegistrationHasCourse
 *
 * @ORM\Table(name="registration_has_course")
 * @ORM\Entity(repositoryClass="AppBundle\Repository\RegistrationHasCourseRepository")
 */
class RegistrationHasCourse extends Timestampable
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
	 * @ORM\ManyToOne(targetEntity="AppBundle\Entity\Registration", inversedBy="registrationsHaveCourses", cascade={"persist"})
	 * @ORM\JoinColumn(name="registration_id", referencedColumnName="id", onDelete="CASCADE")
	 */
	private $registration;

	/**
	 * @ORM\ManyToOne(targetEntity="AppBundle\Entity\Registration\Course", inversedBy="registrationsHaveCourses", cascade={"persist"})
	 * @ORM\JoinColumn(name="course_id", referencedColumnName="id", onDelete="CASCADE")
	 */
	private $course;

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
	 * @return Course
	 */
	public function getCourse()
	{
		return $this->course;
	}

	/**
	 * @param Course $course
	 */
	public function setCourse($course)
	{
		$this->course = $course;
	}

	public function __toString()
	{
		return $this->value;
	}
}