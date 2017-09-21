<?php


namespace AppBundle\Event;


use Symfony\Component\EventDispatcher\Event;
use AppBundle\Entity\CreativityProposal;

class ProposalEvent extends Event
{
	protected $proposal;

	public function __construct(CreativityProposal $proposal)
	{
	    $this->proposal = $proposal;
	}

	public function getProposal()
	{
		return $this->proposal;
	}
}