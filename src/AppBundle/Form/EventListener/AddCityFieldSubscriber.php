<?php


namespace AppBundle\Form\EventListener;

use Symfony\Component\Form\FormEvent;
use Symfony\Component\Form\FormEvents;
use Symfony\Component\Form\FormFactoryInterface;
use Symfony\Component\EventDispatcher\EventSubscriberInterface;
use Doctrine\ORM\EntityRepository;
use AppBundle\Entity\Province;
use Symfony\Component\Form\FormInterface;

class AddCityFieldSubscriber implements EventSubscriberInterface
{
	private $factory;
	private $options;

	public function __construct(FormFactoryInterface $factory, $options = array())
	{
		$this->factory = $factory;
		$this->options = $options;
	}

	public static function getSubscribedEvents()
	{
		return array(
			FormEvents::PRE_SET_DATA => 'preSetData',
			FormEvents::PRE_SUBMIT     => 'preBind'
		);
	}

	private function addCityForm(FormInterface $form, $city, $province)
	{
		$form->add($this->factory->createNamed('city', 'entity', $city, array(
			'class'         => 'AppBundle\Entity\City',
			'auto_initialize' => false,
			'mapped' => true,
			'empty_value'   => 'city.form.empty_value',
			'query_builder' => function (EntityRepository $repository) use ($province) {
				$qb = $repository->createQueryBuilder('city')
					->innerJoin('city.province', 'province');
				if ($province instanceof Province) {
					$qb->where('city.province = :province')
						->setParameter('province', $province);
				} elseif (is_numeric($province)) {
					$qb->where('province.id = :province')
						->setParameter('province', $province);
				} else {
					$qb->where('province.name = :province')
						->setParameter('province', null);
				}

				return $qb;
			},
			'attr' => array('class' => 'city_selector'),
			'label' => 'Ciudad *',
			'label_attr' => array('class' => ''),
			'required' => false,
		)));
	}

	public function preSetData(FormEvent $event)
	{
		$data = $event->getData();
		$form = $event->getForm();

		$province = null;
		$city = null;
		if (null !== $data) {
			$city = ($data->getCity()) ? $data->getCity() : null ;
			$province = ($city) ? $city->getProvince() : null ;
		}

		$this->addCityForm($form, $city, $province);
	}

	public function preBind(FormEvent $event)
	{
		$data = $event->getData();
		$form = $event->getForm();

		if (null === $data) {
			return;
		}

		$city = (array_key_exists('city', $data) && $data['city'] != -1) ? $data['city'] : null;
		$province = (array_key_exists('province', $data) && $data['province'] != -1) ? $data['province'] : null;
		$this->addCityForm($form, $city, $province);
	}
}