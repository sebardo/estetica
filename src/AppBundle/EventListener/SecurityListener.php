<?php


namespace AppBundle\EventListener;

use AppBundle\Entity\Client;
use Symfony\Component\Routing\Router;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Security\Http\Event\InteractiveLoginEvent;

class SecurityListener
{
	/**
	 * @var Router
	 */
	private $router;
	/**
	 * @param Router $router
	 */
	public function __construct(Router $router)
	{
		$this->router = $router;
	}

	/**
	 * @param InteractiveLoginEvent $event
	 */
	public function onSecurityInteractiveLogin(InteractiveLoginEvent $event)
	{
		echo '<pre>';
		print_r("Hola");
		echo '</pre>';die();
		/** @var Client $client */
		$client = $event->getAuthenticationToken()->getUser();
		/** @var Request $request  */
		$request = $event->getRequest();
		if ($client instanceof Client) {
			if(!$client->hasRole('ROLE_ADMIN')){
				$request->request->set('_target_path', $this->router->generate('homepage'));
			}
		}
	}
}