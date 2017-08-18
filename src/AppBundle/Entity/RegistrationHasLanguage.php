<?php


namespace AppBundle\Entity;

use AppBundle\Entity\Registration\Language;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Validator\Constraints as Assert;

/**
 * RegistrationHasLanguage
 *
 * @ORM\Table(name="registration_has_language")
 * @ORM\Entity(repositoryClass="AppBundle\Repository\RegistrationHasLanguageRepository")
 */
class RegistrationHasLanguage extends Timestampable
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
	 * @ORM\ManyToOne(targetEntity="AppBundle\Entity\Registration", inversedBy="registrationsHaveLanguages", cascade={"persist"})
	 * @ORM\JoinColumn(name="registration_id", referencedColumnName="id", onDelete="CASCADE")
	 */
	private $registration;

	/**
	 * @ORM\ManyToOne(targetEntity="AppBundle\Entity\Registration\Language", inversedBy="registrationsHaveLanguages", cascade={"persist"})
	 * @ORM\JoinColumn(name="language_id", referencedColumnName="id", onDelete="CASCADE")
	 */
	private $language;

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
	 * @return Language
	 */
	public function getLanguage()
	{
		return $this->language;
	}

	/**
	 * @param Language $language
	 */
	public function setLanguage($language)
	{
		$this->language = $language;
	}

	public function __toString()
	{
		return $this->value;
	}
}