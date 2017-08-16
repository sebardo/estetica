<?php


namespace AppBundle\Form;

use AppBundle\Entity\MultimediaCategory;
use AppBundle\Model\MultimediaCategoryModel;
use Symfony\Bundle\FrameworkBundle\Routing\Router;
use Symfony\Component\Form\FormFactory;

class FilterMultimediaCategory
{
	/** @var FormFactory $formFactory */
	private $formFactory;

	/** @var Router $router */
	private $router;

	/** @var MultimediaCategoryModel $multimediaCategoryModel */
	private $multimediaCategoryModel;

	/** @var array $categoryCollection */
	private $categoryCollection;

	public function __construct(FormFactory $formFactory, Router $router, MultimediaCategoryModel $multimediaCategoryModel)
	{
		$this->formFactory = $formFactory;
		$this->router = $router;
		$this->multimediaCategoryModel = $multimediaCategoryModel;
		$this->categoryCollection = array(
			'-1' => 'multimedia.form.category_default'
		);
		$this->getCategoryMultimediaCollection();
	}

	private function getCategoryMultimediaCollection()
	{
		$_categoryCollection = $this->multimediaCategoryModel->getBy(array());

		foreach($_categoryCollection as $_category){
			if($_category instanceof MultimediaCategory){
				$this->categoryCollection[$_category->getId()] = $_category->getName();
			}
		}
	}

	public function buildForm($value)
	{
		return $this->formFactory->createBuilder()
			->add('multimedia-category', 'Symfony\Component\Form\Extension\Core\Type\ChoiceType', array(
				'choices' => $this->categoryCollection,
				'label' => 'multimedia.form.category',
				'required' => true,
				'attr' => array(
					'class' => 'no-required'
				),
				'data' => empty($value) ? -1 : $value
			))
			->setAction($this->router->generate('admin_multimedia_list'))
			->setMethod('GET')
			->getForm();
	}
}