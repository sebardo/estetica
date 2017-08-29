<?php


namespace AppBundle\Services;


use AppBundle\Entity\Client;

class Mailer
{
	/** @var  \Swift_Mailer $mailer */
	private $mailer;

	private $templating;

	public function __construct(\Swift_Mailer $mailer, $templating)
	{
		$this->mailer = $mailer;
		$this->templating = $templating;
	}

	public function sendMail(Client $entity, $plainPassword)
	{
		$message = \Swift_Message::newInstance()
			->setSubject('Hello Email')
			->setFrom('send@example.com')
			->setTo('cargarg7@gmail.com')
			->setBody($this->templating->render('AppBundle:Mail:welcome.txt.twig', array('username' => $entity->getUsername(), 'plainPassword' => $plainPassword)))
		;
		$this->mailer->send($message);
	}
}