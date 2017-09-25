<?php


namespace AppBundle\Controller;

use AppBundle\Entity\Creativity;
use AppBundle\Services\Slugify;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Method;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Security;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\ParamConverter;
use BackendBundle\Controller\DefaultController as BackendBundleController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;

/**
 * Class CreativityOrderController
 * @Route("/admin/creativity-order")
 */
class CreativityOrderController extends BackendBundleController
{
	protected $activeSideBar = 'creativity-order';

	protected function getBreadCrumbs($url = null, $data = array())
	{
		$breadcrumbs = parent::getBreadCrumbs($url);

		$breadcrumbs[] = array(
			'name' => 'creativity_order.title_nav'
		);

		if($url){
			$breadcrumbs[1]['url'] = $this->generateUrl('admin_creativity_selection');
		}

		if($data && is_array($data)){
			$breadcrumbs[] = $data;
		}

		return $breadcrumbs;
	}

	/**
	 * Selection creativity.
	 *
	 * @Route("/creativity/selection", name="admin_creativity_selection")
	 * @Method({"GET", "POST"})
	 * @Security("has_role('ROLE_CLIENT')")
	 * @return Response
	 */
	public function selectionCustomCreativityAction()
	{
		$supportCollection = Creativity::getSelectSupports();
		$categoryCollection = Creativity::getSelectCategories();

		return $this->render(
			'AppBundle:CreativityOrder:index.html.twig',
			array(
				'supportCollection' => $supportCollection,
				'categoryCollection' => $categoryCollection,
				'breadcrumbs' => $this->getBreadCrumbs(false),
				'active_side_bar' => $this->getActiveSidebar()
			)
		);
	}

	/**
	 * Create custom creativity on front.
	 *
	 * @param Request $request
	 * @param Creativity $creativity
	 *
	 * @Route("/creativity/{id}/custom", name="admin_creativity_order_custom_create")
	 * @ParamConverter("post", class="AppBundle:Creativity")
	 * @Method({"GET", "POST"})
	 * @Security("has_role('ROLE_CLIENT')")
	 * @return Response
	 */
	public function createCustomCreativityAction(Request $request, Creativity $creativity)
	{
		return $this->render(
			'AppBundle:CreativityOrder:create.html.twig',
			array(
				'breadcrumbs' => $this->getBreadCrumbs(false),
				'active_side_bar' => $this->getActiveSidebar()
			)
		);
	}
}