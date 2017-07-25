<?php


namespace AppBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\MappedSuperclass
 * @ORM\HasLifecycleCallbacks()
 */
class Timestampable
{
	/**
	 * @var \DateTime
	 *
	 * @ORM\Column(name="created_at", type="datetime")
	 */
	protected $createdAt;

	/**
	 * @var \DateTime
	 *
	 * @ORM\Column(name="updated_at", type="datetime")
	 */
	protected $updatedAt;

	/**
	 * Timestampable constructor.
	 */
	public function __construct()
	{
		$this->createdAt = new \DateTime();
	}

	/**
	 * Get createdAt
	 *
	 * @return \DateTime
	 */
	public function getCreatedAt()
	{
		return $this->createdAt;
	}

	/**
	 * Set _createdAt
	 *
	 * @ORM\PrePersist
	 */
	public function setCreatedAt()
	{
		$this->createdAt = new \DateTime();
		$this->updatedAt = new \DateTime();
	}

	/**
	 * Get updatedAt
	 *
	 * @return \DateTime
	 */
	public function getUpdatedAt()
	{
		return $this->updatedAt;
	}

	/**
	 * Set updatedAt
	 *
	 * @ORM\PreUpdate
	 */
	public function setUpdatedAt()
	{
		$this->updatedAt = new \DateTime();
	}
}