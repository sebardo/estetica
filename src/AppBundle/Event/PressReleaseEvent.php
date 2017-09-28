<?php


namespace AppBundle\Event;


use AppBundle\Entity\PressRelease;
use Symfony\Component\EventDispatcher\Event;

class PressReleaseEvent extends Event
{
	protected $pressRelease;

	public function __construct(PressRelease $pressRelease)
	{
		$this->pressRelease = $pressRelease;
	}

	public function getPressRelease()
	{
		return $this->pressRelease;
	}
}