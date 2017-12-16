<?php


namespace AppBundle\Form\EventListener;


use AppBundle\Entity\Registration;
use AppBundle\Entity\Registration\Course;
use AppBundle\Entity\RegistrationHasCourse;
use AppBundle\Form\Registration\CourseType;
use AppBundle\Model\CourseModel;
use Symfony\Component\DependencyInjection\ContainerInterface;
use Symfony\Component\EventDispatcher\EventSubscriberInterface;
use Symfony\Component\Form\FormError;
use Symfony\Component\Form\FormEvent;
use Symfony\Component\Form\FormEvents;
use Symfony\Component\Form\FormInterface;

class AddCourseFieldSubscriber implements EventSubscriberInterface
{
	public function __construct(ContainerInterface $container, $options = array())
	{
		$this->container = $container;
		$this->options = $options;
	}

	public static function getSubscribedEvents()
	{
		return array(
			FormEvents::PRE_SET_DATA => 'preSetData',
			FormEvents::PRE_SUBMIT     => 'preBind'
		);
	}

	private function addCourseForm(FormInterface $form, $data)
	{
		$form
			->add('course', new CourseType($this->container->get('webapp.manager.course_manager'), $this->container->get('webapp.manager.registration_has_course_manager'), $data), array(
				'label' => 'registration.form.course.name',
				'required' => false,
				'label_attr' => array('class' => ''),
				'attr' => array('class' => ''),
				'mapped' => false
			))
		;
	}

	public function preSetData(FormEvent $event)
	{
		$data = $event->getData();
		$form = $event->getForm();

		$this->addCourseForm($form, $data);
	}

	public function preBind(FormEvent $event)
	{
		$data = $event->getData();
		$form = $event->getForm();

		if (null === $data) {
			return;
		}

		//Course
		$count = 0;
		/** @var CourseModel $courseManager */
		$courseManager = $this->container->get('webapp.manager.course_manager');
		// Delete all registrationHasCourses
		$this->deleteRegistrationHasCourseCollectionByRegistrationEntity($form->getData());
		$extra = 'detail';
		$courseName = 'course';
		$courseCollection = (array_key_exists($courseName, $data)) ? $data[$courseName] : null;
		foreach ($courseCollection as $key => $value) {
			if(strpos($key, $extra) === false){
				$id = explode('_', $key)[1];
				$detailKey = $key . '_' . $extra;
				if(array_key_exists($detailKey, $data[$courseName])){
					/** @var Course $course */
					$course = $courseManager->getOneBy(array('id' => $id));
					if(!empty($course)){
						$extraData = $data[$courseName][$detailKey];
						if(!empty($extraData)) {
							$entity = new RegistrationHasCourse();
							$entity->setRegistration($form->getData());
							$entity->setCourse($course);
							$entity->setValue($extraData);
							$courseManager->save($entity, false);
						} else {
							$formError = new FormError($this->container->get('translator')->trans('registration.form.errors.course.detail', array('%field%' => $course->getName())), null, array(), null, $courseName);
							$form->addError($formError);
						}
						$count++;
					}
				}
			}
		}

		if($count == 0) {
			$formError = new FormError($this->container->get('translator')->trans('registration.form.errors.course.empty'), null, array(), null, 'course');
			$form->addError($formError);
		}
	}

	private function deleteRegistrationHasCourseCollectionByRegistrationEntity(Registration $entity)
	{
		$registrationHasCourseModel = $this->container->get('webapp.manager.registration_has_course_manager');
		$registrationHasCourseCollection = $registrationHasCourseModel->getBy(array('registration' => $entity));
		foreach ($registrationHasCourseCollection as $registrationHasCourse) {
			$registrationHasCourseModel->remove($registrationHasCourse);
		}
	}
}