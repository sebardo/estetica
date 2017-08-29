<?php


namespace AppBundle\Form\EventListener;

use Symfony\Component\Form\FormEvent;
use Symfony\Component\Form\FormEvents;
use Symfony\Component\Form\FormFactoryInterface;
use Symfony\Component\EventDispatcher\EventSubscriberInterface;
use Doctrine\ORM\EntityRepository;
use AppBundle\Entity\Country;
use Symfony\Component\Form\FormInterface;

class AddProvinceFieldSubscriber implements EventSubscriberInterface
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

	private function addProvinceForm(FormInterface $form, $province, $country)
	{
		$form->add($this->factory->createNamed('province', 'entity', $province, array(
			'class'         => 'AppBundle\Entity\Province',
			'auto_initialize' => false,
			'empty_value'   => 'province.form.empty_value',
			'mapped'        => false,
			'query_builder' => function (EntityRepository $repository) use ($country) {
				$qb = $repository->createQueryBuilder('province')
					->innerJoin('province.country', 'country');
				if($country instanceof Country){
					$qb->where('province.country = :country')
						->setParameter('country', $country);
				}elseif(is_numeric($country)){
					$qb->where('country.id = :country')
						->setParameter('country', $country);
				}else{
					$qb->where('country.name = :country')
						->setParameter('country', null);
				}

				return $qb;
			},
			'attr' => array('class' => 'province_selector'),
			'label' => 'province.form.name',
			'label_attr' => array('class' => ''),
			'required' => (array_key_exists('required_form', $this->options)) ? $this->options['required_form'] : true,
		)));
	}

	public function preSetData(FormEvent $event)
	{
		$data = $event->getData();
		$form = $event->getForm();

		$province = null;
		$country = null;
		if (null !== $data) {
			$province = ($data->getCity()) ? $data->getCity()->getProvince() : null ;
			$country = ($province) ? $province->getCountry() : null ;
		}

		$this->addProvinceForm($form, $province, $country);
	}

	public function preBind(FormEvent $event)
	{
		$data = $event->getData();
		$form = $event->getForm();

		if (null === $data) {
			return;
		}

		$province = array_key_exists('province', $data) ? $data['province'] : null;
		$country = array_key_exists('country', $data) ? $data['country'] : null;
		$this->addProvinceForm($form, $province, $country);
	}
}