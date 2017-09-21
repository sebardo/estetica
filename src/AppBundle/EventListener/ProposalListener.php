<?php


namespace AppBundle\EventListener;

use AppBundle\Event\ProposalEvent;

class ProposalListener
{
	protected $twig;
	protected $mailer;
	protected $sender;
	protected $receiver;

	public function __construct(\Twig_Environment $twig, \Swift_Mailer $mailer, $sender, $receiver)
	{
		$this->twig = $twig;
		$this->mailer = $mailer;
		$this->sender = $sender;
		$this->receiver = $receiver;
	}
	public function onProposalCreated(ProposalEvent $event)
	{
		$proposal = $event->getProposal();
		$body = $this->renderTemplate($proposal);

		$message = \Swift_Message::newInstance()
			->setSubject('Proposal ' . $proposal->getId() . ' created')
			->setFrom($this->sender)
			->setTo($this->receiver)
			->setBody($body)
		;
		$this->mailer->send($message);
	}

	public function renderTemplate($proposal)
	{
		return $this->twig->render(
			'AppBundle:Mail:proposal_created.txt.twig',
			array(
				'proposal' => $proposal
			)
		);
	}
}