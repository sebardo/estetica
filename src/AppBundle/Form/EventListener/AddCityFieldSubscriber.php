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

	private function addCityForm(FormInterface $form, $province)
	{
		$form->add($this->factory->createNamed('city','entity', null, array(
			'class'         => 'AppBundle\Entity\City',
			'auto_initialize' => false,
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
			'label' => 'city.form.name',
			'label_attr' => array('class' => ''),
			'required' => (array_key_exists('required_form', $this->options)) ? $this->options['required_form'] : true,
		)));
	}

	public function preSetData(FormEvent $event)
	{
		$data = $event->getData();
		$form = $event->getForm();

		$province = null;
		if (null !== $data) {
			$province = ($data->city) ? $data->city->getProvince() : null ;
		}

		$this->addCityForm($form, $province);
	}

	public function preBind(FormEvent $event)
	{
		$data = $event->getData();
		$form = $event->getForm();

		if (null === $data) {
			return;
		}

		$province = array_key_exists('province', $data) ? $data['province'] : null;
		$this->addCityForm($form, $province);
	}
}