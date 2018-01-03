<?php


namespace AppBundle\Event;


use Symfony\Component\EventDispatcher\Event;
use EditorBundle\Entity\Template as Templating;

class OrderEvent extends Event
{
	protected $order;

	public function __construct(Templating $order)
	{
		$this->order = $order;
	}

	public function getOrder()
	{
		return $this->order;
	}
}