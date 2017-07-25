<?php


namespace AppBundle\EventListener;

use AppBundle\Entity\User;
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
		/** @var User $user */
		$user = $event->getAuthenticationToken()->getUser();
		/** @var Request $request  */
		$request = $event->getRequest();
		if ($user instanceof User) {
			if(!$user->hasRole('ROLE_ADMIN')){
				//TODO: put the correct redirect when is client ROLE
			}
		}
	}
}