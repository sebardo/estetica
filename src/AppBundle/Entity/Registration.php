<?php


namespace AppBundle\Entity;

use AppBundle\Entity\Registration\Experience;
use AppBundle\Entity\Registration\PlaceResidence;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\HttpFoundation\File\UploadedFile;
use Vich\UploaderBundle\Mapping\Annotation as Vich;
use Symfony\Component\Validator\Constraints as Assert;

/**
 * Registration
 *
 * @ORM\Table(name="registration")
 * @ORM\Entity(repositoryClass="AppBundle\Repository\RegistrationRepository")
 */
class Registration extends Timestampable
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
	 * @ORM\Column(name="name", type="string", length=255)
	 */
	private $name;

	/**
	 * @var string
	 *
	 * @ORM\Column(name="first_lastname", type="string", length=255)
	 */
	private $firstLastname;

	/**
	 * @var string
	 *
	 * @ORM\Column(name="second_lastname", type="string", length=255)
	 */
	private $secondLastname;

	/**
	 * @var string
	 *
	 * @ORM\Column(name="phone", type="string", length=255)
	 */
	private $phone;

	/**
	 * @var string
	 *
	 * @ORM\Column(name="mobile", type="string", length=255)
	 */
	private $mobile;

	/**
	 * @var string
	 *
	 * @ORM\Column(name="email", type="string", length=255)
	 */
	private $email;

	/**
	 * @var string
	 *
	 * @ORM\Column(name="gender", type="string", length=255)
	 */
	private $gender;

	/**
	 * @var \DateTime
	 *
	 * @ORM\Column(name="birthday", type="datetime")
	 */
	private $birthday;

	/**
	 * @var string
	 *
	 * @ORM\Column(name="photo", type="string", length=255)
	 */
	private $photoPath;

	/**
	 * @Assert\File(
	 *     mimeTypes = "image/*",
	 *     mimeTypesMessage = "Please upload a valid image"
	 * )
	 * @Assert\File(
	 *     maxSize = "5M",
	 *	   maxSizeMessage = "Please upload an image with max size"
	 * )
	 */
	private $photoFile;

	/**
	 * @var string
	 *
	 * @ORM\Column(name="cv", type="string", length=255)
	 */
	private $cvPath;

	/**
	 * @Assert\File(
	 *     mimeTypes = "application/pdf",
	 *     mimeTypesMessage = "Please upload a valid file"
	 * )
	 * @Assert\File(
	 *     maxSize = "5M",
	 *	   maxSizeMessage = "Please upload a file with max size"
	 * )
	 */
	private $cvFile;

	/**
	 * @var boolean
	 *
	 * @ORM\Column(name="vehicle", type="boolean")
	 */
	private $vehicle;

	/**
	 * @var boolean
	 *
	 * @ORM\Column(name="travel_availability", type="boolean")
	 */
	private $travelAvailability;

	/**
	 * @var boolean
	 *
	 * @ORM\Column(name="certificate_disability", type="boolean")
	 */
	private $certificateDisability;

	/**
	 * @var boolean
	 *
	 * @ORM\Column(name="sales_training", type="boolean")
	 */
	private $salesTraining;

	/**
	 * @ORM\ManyToOne(targetEntity="AppBundle\Entity\Registration\Experience", inversedBy="registrations", cascade={"persist"})
	 * @ORM\JoinColumn(name="experience_id", referencedColumnName="id", onDelete="CASCADE")
	 */
	private $experience;

	/**
	 * @var ArrayCollection
	 *
	 * @ORM\OneToMany(targetEntity="AppBundle\Entity\RegistrationHasSpeciality", mappedBy="registration", cascade={"persist"})
	 */
	private $registrationsHaveSpecialities;

	/**
	 * @var ArrayCollection
	 *
	 * @ORM\OneToMany(targetEntity="AppBundle\Entity\RegistrationHasLanguage", mappedBy="registration", cascade={"persist"})
	 */
	private $registrationsHaveLanguages;

	/**
	 * @var ArrayCollection
	 *
	 * @ORM\OneToMany(targetEntity="AppBundle\Entity\RegistrationHasCourse", mappedBy="registration", cascade={"persist"})
	 */
	private $registrationsHaveCourses;

	/**
	 * @ORM\ManyToMany(targetEntity="AppBundle\Entity\Registration\TimeAvailability", inversedBy="registrations")
	 * @ORM\JoinTable(name="registrations_times")
	 * @Assert\Count(min = 1, minMessage = "registration.min_one")
	 */
	private $timesAvailability;

	/**
	 * @ORM\ManyToMany(targetEntity="AppBundle\Entity\Registration\ContractType", inversedBy="registrations")
	 * @ORM\JoinTable(name="registrations_contracts")
	 * @Assert\Count(min = 1, minMessage = "registration.min_one")
	 */
	private $contractTypes;

	/**
	 * @ORM\ManyToMany(targetEntity="AppBundle\Entity\Registration\LevelResponsibility", inversedBy="registrations")
	 * @ORM\JoinTable(name="registrations_responsibilities")
	 * @Assert\Count(min = 1, minMessage = "registration.min_one")
	 */
	private $levelsResponsibility;

	/**
	 * @ORM\OneToOne(targetEntity="AppBundle\Entity\Registration\PlaceResidence", inversedBy="registration", cascade={"persist"})
	 * @ORM\JoinColumn(name="place_residence_id", referencedColumnName="id")
	 */
	private $placeResidence;

	/**
	 * @ORM\ManyToMany(targetEntity="AppBundle\Entity\Registration\Study", inversedBy="registrations")
	 * @ORM\JoinTable(name="registrations_studies")
	 */
	private $studies;

	/**
	 * @ORM\ManyToMany(targetEntity="AppBundle\Entity\AcademicStudy", inversedBy="registrations")
	 * @ORM\JoinTable(name="registrations_academics")
	 * @Assert\Count(min = 1, minMessage = "registration.min_one")
	 */
	private $academicStudies;

	public function __construct()
	{
		parent::__construct();
		$this->vehicle = false;
		$this->registrationsHaveSpecialities = new ArrayCollection();
		$this->registrationsHaveLanguages = new ArrayCollection();
		$this->registrationsHaveCourses = new ArrayCollection();
		$this->timesAvailability = new ArrayCollection();
		$this->contractTypes = new ArrayCollection();
		$this->levelsResponsibility = new ArrayCollection();
		$this->studies = new ArrayCollection();
		$this->academicStudies = new ArrayCollection();
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
	}

	/**
	 * @return string
	 */
	public function getFirstLastname()
	{
		return $this->firstLastname;
	}

	/**
	 * @param string $firstLastname
	 */
	public function setFirstLastname($firstLastname)
	{
		$this->firstLastname = $firstLastname;
	}

	/**
	 * @return string
	 */
	public function getSecondLastname()
	{
		return $this->secondLastname;
	}

	/**
	 * @param string $secondLastname
	 */
	public function setSecondLastname($secondLastname)
	{
		$this->secondLastname = $secondLastname;
	}

	/**
	 * @return string
	 */
	public function getPhone()
	{
		return $this->phone;
	}

	/**
	 * @param string $phone
	 */
	public function setPhone($phone)
	{
		$this->phone = $phone;
	}

	/**
	 * @return string
	 */
	public function getMobile()
	{
		return $this->mobile;
	}

	/**
	 * @param string $mobile
	 */
	public function setMobile($mobile)
	{
		$this->mobile = $mobile;
	}

	/**
	 * @return string
	 */
	public function getEmail()
	{
		return $this->email;
	}

	/**
	 * @param string $email
	 */
	public function setEmail($email)
	{
		$this->email = $email;
	}

	/**
	 * @return string
	 */
	public function getPhotoPath()
	{
		return $this->photoPath;
	}

	/**
	 * @param string $photoPath
	 */
	public function setPhotoPath($photoPath)
	{
		$this->photoPath = $photoPath;
	}

	public function uploadPhoto($directory)
	{
		if (null === $this->getPhotoFile()) {
			return;
		}

		$fileName = uniqid('registration-').'.'.$this->getPhotoFile()->guessExtension();

		$this->getPhotoFile()->move($directory, $fileName);

		$this->setPhotoPath($fileName);

		unset($this->photoFile);
	}

	/**
	 * @return UploadedFile
	 */
	public function getPhotoFile()
	{
		return $this->photoFile;
	}

	/**
	 * @param UploadedFile $photoFile
	 */
	public function setPhotoFile(UploadedFile $photoFile = null)
	{
		$this->photoFile = $photoFile;
	}

	/**
	 * @return string
	 */
	public function getGender()
	{
		return $this->gender;
	}

	/**
	 * @param string $gender
	 */
	public function setGender($gender)
	{
		$this->gender = $gender;
	}

	/**
	 * @return \DateTime
	 */
	public function getBirthday()
	{
		return $this->birthday;
	}

	/**
	 * @param \DateTime $birthday
	 */
	public function setBirthday($birthday)
	{
		$this->birthday = $birthday;
	}

	/**
	 * @return boolean
	 */
	public function isVehicle()
	{
		return $this->vehicle;
	}

	/**
	 * @param boolean $vehicle
	 */
	public function setVehicle($vehicle)
	{
		$this->vehicle = $vehicle;
	}

	/**
	 * @return boolean
	 */
	public function isTravelAvailability()
	{
		return $this->travelAvailability;
	}

	/**
	 * @param boolean $travelAvailability
	 */
	public function setTravelAvailability($travelAvailability)
	{
		$this->travelAvailability = $travelAvailability;
	}

	/**
	 * @return boolean
	 */
	public function isCertificateDisability()
	{
		return $this->certificateDisability;
	}

	/**
	 * @param boolean $certificateDisability
	 */
	public function setCertificateDisability($certificateDisability)
	{
		$this->certificateDisability = $certificateDisability;
	}

	/**
	 * @return boolean
	 */
	public function isSalesTraining()
	{
		return $this->salesTraining;
	}

	/**
	 * @param boolean $salesTraining
	 */
	public function setSalesTraining($salesTraining)
	{
		$this->salesTraining = $salesTraining;
	}

	/**
	 * @return Experience
	 */
	public function getExperience()
	{
		return $this->experience;
	}

	/**
	 * @param Experience $experience
	 */
	public function setExperience($experience)
	{
		$this->experience = $experience;
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

	/**
	 * @return mixed
	 */
	public function getTimesAvailability()
	{
		return $this->timesAvailability;
	}

	/**
	 * @param mixed $timesAvailability
	 */
	public function setTimesAvailability($timesAvailability)
	{
		$this->timesAvailability = $timesAvailability;
	}

	public function addTimeAvailability($timeAvailability)
	{
		if($this->timesAvailability->contains($timeAvailability)){
			return;
		}

		$this->timesAvailability->add($timeAvailability);
	}

	public function removeTimeAvailability($timeAvailability)
	{
		if(!$this->timesAvailability->contains($timeAvailability)){
			return;
		}

		$this->timesAvailability->remove($timeAvailability);
	}

	/**
	 * @return mixed
	 */
	public function getContractTypes()
	{
		return $this->contractTypes;
	}

	/**
	 * @param mixed $contractTypes
	 */
	public function setContractTypes($contractTypes)
	{
		$this->contractTypes = $contractTypes;
	}

	public function addContractType($contractType)
	{
		if($this->contractTypes->contains($contractType)){
			return;
		}

		$this->contractTypes->add($contractType);
	}

	public function removeContractType($contractType)
	{
		if(!$this->contractTypes->contains($contractType)){
			return;
		}

		$this->contractTypes->remove($contractType);
	}

	/**
	 * @return mixed
	 */
	public function getLevelsResponsibility()
	{
		return $this->levelsResponsibility;
	}

	/**
	 * @param mixed $levelsResponsibility
	 */
	public function setLevelsResponsibility($levelsResponsibility)
	{
		$this->levelsResponsibility = $levelsResponsibility;
	}

	public function addLevelResponsibility($levelResponsibility)
	{
		if($this->timesAvailability->contains($levelResponsibility)){
			return;
		}

		$this->timesAvailability->add($levelResponsibility);
	}

	public function removeLevelResponsibility($levelResponsibility)
	{
		if(!$this->timesAvailability->contains($levelResponsibility)){
			return;
		}

		$this->timesAvailability->remove($levelResponsibility);
	}

	/**
	 * @return PlaceResidence
	 */
	public function getPlaceResidence()
	{
		return $this->placeResidence;
	}

	/**
	 * @param PlaceResidence $placeResidence
	 */
	public function setPlaceResidence($placeResidence)
	{
		$this->placeResidence = $placeResidence;
	}

	/**
	 * @return mixed
	 */
	public function getStudies()
	{
		return $this->studies;
	}

	/**
	 * @param mixed $studies
	 */
	public function setStudies($studies)
	{
		$this->studies = $studies;
	}

	public function addStudy($study)
	{
		if($this->studies->contains($study)){
			return;
		}

		$this->studies->add($study);
	}

	public function removeStudy($study)
	{
		if(!$this->studies->contains($study)){
			return;
		}

		$this->studies->remove($study);
	}

	/**
	 * @return mixed
	 */
	public function getAcademicStudies()
	{
		return $this->academicStudies;
	}

	/**
	 * @param mixed $academicStudies
	 */
	public function setAcademicStudies($academicStudies)
	{
		$this->academicStudies = $academicStudies;
	}

	public function addAcademicStudy($academicStudy)
	{
		if($this->academicStudies->contains($academicStudy)){
			return;
		}

		$this->academicStudies->add($academicStudy);
	}

	public function removeAcademicStudy($academicStudy)
	{
		if(!$this->academicStudies->contains($academicStudy)){
			return;
		}

		$this->academicStudies->remove($academicStudy);
	}

	public function uploadCV($directory)
	{
		if (null === $this->getCvFile()) {
			return;
		}

		$fileName = uniqid('registration-cv-').'.'.$this->getCvFile()->guessExtension();

		$this->getCvFile()->move($directory, $fileName);

		$this->setCvPath($fileName);

		unset($this->cvFile);
	}

	/**
	 * @return string
	 */
	public function getCvPath()
	{
		return $this->cvPath;
	}

	/**
	 * @param string $cvPath
	 */
	public function setCvPath($cvPath)
	{
		$this->cvPath = $cvPath;
	}

	/**
	 * @return UploadedFile
	 */
	public function getCvFile()
	{
		return $this->cvFile;
	}

	/**
	 * @param UploadedFile $cvFile
	 */
	public function setCvFile(UploadedFile $cvFile = null)
	{
		$this->cvFile = $cvFile;
	}

	public function __toString()
	{
		return (string)$this->id;
	}
}