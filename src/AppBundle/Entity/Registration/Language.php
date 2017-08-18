<?php


namespace AppBundle\Entity\Registration;

use AppBundle\Services\Formatting;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Validator\Constraints as Assert;

/**
 * Language
 *
 * @ORM\Table(name="language")
 * @ORM\Entity(repositoryClass="AppBundle\Repository\Registration\LanguageRepository")
 */
class Language
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
	 * @ORM\OneToMany(targetEntity="AppBundle\Entity\RegistrationHasLanguage", mappedBy="language", cascade={"persist"})
	 */
	private $registrationsHaveLanguages;

	public function __construct()
	{
		$this->registrationsHaveLanguages = new ArrayCollection();
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
	public function getRegistrationsHaveLanguages()
	{
		return $this->registrationsHaveLanguages;
	}

	/**
	 * @param mixed $registrationsHaveLanguages
	 */
	public function setRegistrationsHaveLanguages($registrationsHaveLanguages)
	{
		$this->registrationsHaveLanguages = $registrationsHaveLanguages;
	}

	public function addRegistrationHasLanguage($registrationHasLanguage)
	{
		if($this->registrationsHaveLanguages->contains($registrationHasLanguage)){
			return;
		}

		$this->registrationsHaveLanguages->add($registrationHasLanguage);
	}

	public function removeRegistrationHasLanguage($registrationHasLanguage)
	{
		if(!$this->registrationsHaveLanguages->contains($registrationHasLanguage)){
			return;
		}

		$this->registrationsHaveLanguages->remove($registrationHasLanguage);
	}

	public function __toString()
	{
		return $this->name;
	}
}