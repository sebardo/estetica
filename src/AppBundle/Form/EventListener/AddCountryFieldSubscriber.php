<?php


namespace AppBundle\Form\EventListener;


use Symfony\Component\Form\FormEvent;
use Symfony\Component\Form\FormEvents;
use Symfony\Component\Form\FormFactoryInterface;
use Symfony\Component\EventDispatcher\EventSubscriberInterface;
use Doctrine\ORM\EntityRepository;
use Symfony\Component\Form\FormInterface;

class AddCountryFieldSubscriber implements EventSubscriberInterface
{
	private $factory;

	public function __construct(FormFactoryInterface $factory)
	{
		$this->factory = $factory;
	}

	public static function getSubscribedEvents()
	{
		return array(
			FormEvents::PRE_SET_DATA => 'preSetData',
			FormEvents::PRE_SUBMIT     => 'preBind'
		);
	}

	private function addCountryForm(FormInterface $form, $country)
	{
		$form->add($this->factory->createNamed('country', 'entity', $country, array(
			'class'         => 'AppBundle\Entity\Country',
			'auto_initialize' => false,
			'mapped'        => false,
			'empty_value'   => 'País',
			'query_builder' => function (EntityRepository $repository) {
				$qb = $repository->createQueryBuilder('country');

				return $qb;
			},
			'attr' => array('class' => 'country_selector')
		)));
	}

	public function preSetData(FormEvent $event)
	{
		$data = $event->getData();
		$form = $event->getForm();

		$country = null;
		if (null !== $data) {
			$country = ($data->city) ? $data->city->getProvince()->getCountry() : null ;
		}

		$this->addCountryForm($form, $country);
	}

	public function preBind(FormEvent $event)
	{
		$data = $event->getData();
		$form = $event->getForm();

		if (null === $data) {
			return;
		}

		$country = array_key_exists('country', $data) ? $data['country'] : null;
		$this->addCountryForm($form, $country);
	}
}