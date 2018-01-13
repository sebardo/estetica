<?php


namespace AppBundle\Services;


use AppBundle\Entity\Client;

class Mailer
{
	/** @var  \Swift_Mailer $mailer */
	private $mailer;

	private $templating;
        
        private $adminEmail;

	public function __construct(\Swift_Mailer $mailer, $templating, $adminEmail)
	{
		$this->mailer = $mailer;
		$this->templating = $templating;
		$this->adminEmail = $adminEmail;
	}

	public function sendMail(Client $entity, $plainPassword)
	{
		$message = \Swift_Message::newInstance()
			->setSubject('Hello '.$entity->getUsername())
			->setFrom('send@example.com')
			->setTo($this->adminEmail)
			->setBody($this->templating->render('AppBundle:Mail:welcome.txt.twig', array(
                            'username' => $entity->getUsername(), 
                            'plainPassword' => $plainPassword,
                            'tradeName' => $entity->getTradeName()
                        )))
		;
		$this->mailer->send($message);
	}
}