<?php


namespace AppBundle\EventListener;

use AppBundle\Entity\PressRelease;
use AppBundle\Event\PressReleaseEvent;

class PressReleaseListener
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
	public function onPressReleaseCreated(PressReleaseEvent $event)
	{
		/** @var PressRelease $pressRelease */
		$pressRelease = $event->getPressRelease();
		$body = $this->renderTemplate($pressRelease);

		$message = \Swift_Message::newInstance()
			->setSubject('Noticia creada por ' . $pressRelease->getClient()->getSocietyName())
			->setFrom($this->sender)
			->setTo($this->receiver)
			->setBody($body)
		;
		$this->mailer->send($message);
	}

	public function renderTemplate($pressRelease)
	{
		return $this->twig->render(
			'AppBundle:Mail:press_release_created.txt.twig',
			array(
				'press_release' => $pressRelease
			)
		);
	}
}