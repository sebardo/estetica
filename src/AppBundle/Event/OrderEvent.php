<?php


namespace AppBundle\Event;


use Symfony\Component\EventDispatcher\Event;
use AppBundle\Entity\CreativityOrder;

class OrderEvent extends Event
{
	protected $order;

	public function __construct(CreativityOrder $order)
	{
		$this->order = $order;
	}

	public function getOrder()
	{
		return $this->order;
	}
}