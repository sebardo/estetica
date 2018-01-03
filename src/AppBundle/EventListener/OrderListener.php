<?php


namespace AppBundle\EventListener;

use AppBundle\Entity\CreativityOrder;
use AppBundle\Event\OrderEvent;

class OrderListener
{
	protected $twig;
	protected $mailer;
	protected $sender;
	protected $receiver;
	protected $orderPath;

	public function __construct(\Twig_Environment $twig, \Swift_Mailer $mailer, $sender, $receiver, $orderPath)
	{
		$this->twig = $twig;
		$this->mailer = $mailer;
		$this->sender = $sender;
		$this->receiver = $receiver;
		$this->orderPath = $orderPath;
	}
	public function onOrderCreated(OrderEvent $event)
	{
		/** @var CreativityOrder $order */
		$template = $event->getOrder();
		$body = $this->renderTemplate($template);

		$message = \Swift_Message::newInstance()
			->setSubject('Order ' . $template->getId() . ' created')
			->attach(\Swift_Attachment::fromPath($this->orderPath . '/' . $template->getPdfPath()))
			->setFrom($this->sender)
			->setTo($this->receiver)
			->setBody($body)
		;
		$this->mailer->send($message);
	}

	public function renderTemplate($order)
	{
		return $this->twig->render(
			'AppBundle:Mail:order_created.txt.twig',
			array(
				'order' => $order
			)
		);
	}
}