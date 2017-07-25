<?php


namespace AppBundle\Entity;

use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Validator\Constraints as Assert;
use Symfony\Component\Security\Core\User\UserInterface;

/** @ORM\MappedSuperclass */
class User extends Timestampable implements UserInterface
{
	const ROLE_ADMIN = "ROLE_ADMIN";
	const ROLE_CLIENT = "ROLE_CLIENT";

	/**
	 * @var string
	 *
	 * @ORM\Column(name="id", type="string")
	 * @ORM\Id
	 * @ORM\GeneratedValue(strategy="UUID")
	 */
	protected $id;

	/**
	 * @var string
	 *
	 * @Assert\NotBlank()
	 * @ORM\Column(name="username", type="string", length=255, unique=true)
	 */
	protected $username;

	/**
	 * @var string
	 *
	 * @ORM\Column(name="password", type="string", length=255)
	 */
	protected $password;

	/**
	 * @var boolean
	 *
	 * @ORM\Column(name="active", type="boolean")
	 */
	protected $active;

	/**
	 * @var string
	 *
	 * @ORM\Column(name="role", type="string", length=20)
	 */
	protected $role;

	public function __construct()
	{
	    parent::__construct();
		$this->active = true;
		$this->role = self::ROLE_CLIENT;
	}

	/**
	 * @return string
	 */
	public function getId()
	{
		return $this->id;
	}

	/**
	 * @return string
	 */
	public function getUsername()
	{
		return $this->username;
	}

	/**
	 * @param string $username
	 */
	public function setUsername($username)
	{
		$this->username = $username;
	}

	/**
	 * @return string
	 */
	public function getPassword()
	{
		return $this->password;
	}

	/**
	 * @param string $password
	 */
	public function setPassword($password)
	{
		$this->password = $password;
	}

	/**
	 * @return boolean
	 */
	public function isActive()
	{
		return $this->active;
	}

	/**
	 * @param boolean $active
	 */
	public function setActive($active)
	{
		$this->active = $active;
	}

	/**
	 * @return string
	 */
	public function getRole()
	{
		return $this->role;
	}

	/**
	 * @param string $role
	 */
	public function setRole($role)
	{
		$this->role = $role;
	}

	/**
	 * @return array
	 */
	public function getRoles()
	{
		return explode(',', $this->role);
	}

	/**
	 * @param string $role
	 */
	public function setRoles($role)
	{
		$this->role = $role;
	}

	public function addRole($role)
	{
		$this->role .= ",".$role;
	}

	public function hasRole($role)
	{
		if(in_array($role, $this->getRoles())){
			return true;
		}
		return false;
	}

	/**
	 * Returns the salt that was originally used to encode the password.
	 *
	 * This can return null if the password was not encoded using a salt.
	 *
	 * @return string|null The salt
	 */
	public function getSalt()
	{
		// TODO: Implement getSalt() method.
	}

	/**
	 * Removes sensitive data from the user.
	 *
	 * This is important if, at any given point, sensitive information like
	 * the plain-text password is stored on this object.
	 */
	public function eraseCredentials()
	{
		// TODO: Implement eraseCredentials() method.
	}

	public function __toString()
	{
		return $this->username;
	}
}