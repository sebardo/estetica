<?php


namespace AppBundle\Entity\Registration;

use AppBundle\Entity\Timestampable;
use AppBundle\Services\Formatting;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Validator\Constraints as Assert;

/**
 * ParentSpeciality
 *
 * @ORM\Table(name="parent_speciality")
 * @ORM\Entity(repositoryClass="AppBundle\Repository\Registration\ParentSpecialityRepository")
 */
class ParentSpeciality extends Timestampable
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
	 * @ORM\OneToMany(targetEntity="Speciality", mappedBy="parent", cascade={"persist"})
	 */
	private $specialities;

	public function __construct()
	{
		parent::__construct();
		$this->specialities = new ArrayCollection();
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
	 * @return ArrayCollection
	 */
	public function getSpecialities()
	{
		return $this->specialities;
	}

	/**
	 * @param ArrayCollection $specialities
	 */
	public function setSpecialities($specialities)
	{
		$this->specialities = $specialities;
	}

	/**
	 * @param $speciality
	 */
	public function addSpeciality($speciality)
	{
		if ($this->specialities->contains($speciality)){
			return;
		}

		$this->specialities->add($speciality);
	}

	/**
	 * @param $speciality
	 */
	public function removeSpeciality($speciality)
	{
		if (!$this->specialities->contains($speciality)){
			return;
		}

		$this->specialities->remove($speciality);
	}

	public function __toString()
	{
		return $this->name;
	}
}