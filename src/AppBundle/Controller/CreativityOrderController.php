<?php


namespace AppBundle\Controller;

use AppBundle\Entity\Client;
use AppBundle\Entity\Creativity;
use AppBundle\Entity\CreativityOrder;
use AppBundle\Event\OrderEvent;
use AppBundle\Form\Order\CreativityOrderFieldType;
use AppBundle\Form\Order\CreativityOrderPrintType;
use AppBundle\Form\Order\CreativityOrderDeliveryType;
use AppBundle\OrderEvents;
use AppBundle\Services\ImageHandler;
use AppBundle\Services\Pdf;
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
	 * @ParamConverter("creativity", class="AppBundle:Creativity", options={"id" = "id"})
	 * @Method({"GET", "POST"})
	 * @Security("has_role('ROLE_CLIENT')")
	 * @return Response
	 */
	public function createCustomCreativityAction(Request $request, Creativity $creativity)
	{
		/** @var Client $client */
		$client = $this->container->get('security.token_storage')->getToken()->getUser();
		$entity = new CreativityOrder($creativity, $client);
		$form = $this->createForm(new CreativityOrderFieldType(), $entity, array('support' => $creativity->getSupport()));
		$form->handleRequest($request);

		if ($form->isSubmitted() && $form->isValid()) {
			$em = $this->getDoctrine()->getManager();
			$filename = 'order_' . strtolower($creativity->getSupport()) . '_' . strtolower($client->getSocietyName()) . '_' . time() . '.pdf';
			$filenamePath = ltrim($this->getParameter('app.path.creativity_orders'), '/') . '/' . $filename;
			$pdf = $this->createCompletedPdf($entity, $creativity, $client, $filenamePath);
			$entity->setCreativityOrderPdf($filename);
			$em->persist($entity);
			$em->flush();

			$this->get('session')->getFlashBag()->add('success', $this->get('translator')->trans('creativity_order.create_succesfull'));

			return $this->redirectToRoute('admin_creativity_order_printed', array('id' => $creativity->getId(), 'creativity_order_id' => $entity->getId()));
		}

		return $this->render(
			'AppBundle:CreativityOrder:create.html.twig',
			array(
				'client' => $client,
				'creativity' => $creativity,
				'entity' => $entity,
				'form' => $form->createView(),
				'breadcrumbs' => $this->getBreadCrumbs(false),
				'active_side_bar' => $this->getActiveSidebar()
			)
		);
	}

	/**
	 * Printed custom creativity on front.
	 *
	 * @param Request $request
	 * @param Creativity $creativity
	 * @param CreativityOrder $order
	 *
	 * @Route("/creativity/{id}/custom/{creativity_order_id}/printed", name="admin_creativity_order_printed")
	 * @ParamConverter("creativity", class="AppBundle:Creativity", options={"id" = "id"})
	 * @ParamConverter("order", class="AppBundle:CreativityOrder", options={"id" = "creativity_order_id"})
	 * @Method({"GET", "POST"})
	 * @Security("has_role('ROLE_CLIENT')")
	 * @return Response
	 */
	public function printedCustomCreativityAction(Request $request, Creativity $creativity, CreativityOrder $order)
	{
		/** @var Client $client */
		$client = $this->container->get('security.token_storage')->getToken()->getUser();
		if($order->getClient() !== $client) {
			$this->get('session')->getFlashBag()->add('success', $this->get('translator')->trans('creativity_order.permissions_denied'));

			return $this->redirectToRoute('admin_creativity_selection');
		}

		$form = $this->createForm(new CreativityOrderPrintType(), $order);
		$form->handleRequest($request);

		if ($form->isSubmitted() && $form->isValid()) {
			$em = $this->getDoctrine()->getManager();
			$em->persist($order);
			$em->flush();

			if($order->getPrint()){
				$this->sendNotification($order);
				return $this->redirectToRoute('admin_creativity_order_completed', array('id' => $creativity->getId(), 'creativity_order_id' => $order->getId()));
			}

			$this->get('session')->getFlashBag()->add('success', $this->get('translator')->trans('creativity_order.send_notification_fail'));

			return $this->redirectToRoute('admin_creativity_selection');
		}

		return $this->render(
			'AppBundle:CreativityOrder:print.html.twig',
			array(
				'support' => $creativity->getSupport(),
				'creativity' => $creativity,
				'entity' => $order,
				'form' => $form->createView(),
				'breadcrumbs' => $this->getBreadCrumbs(false),
				'active_side_bar' => $this->getActiveSidebar()
			)
		);
	}

	/**
	 * Completed custom creativity on front.
	 *
	 * @param Request $request
	 * @param Creativity $creativity
	 * @param CreativityOrder $order
	 *
	 * @Route("/creativity/{id}/custom/{creativity_order_id}/completed", name="admin_creativity_order_completed")
	 * @ParamConverter("creativity", class="AppBundle:Creativity", options={"id" = "id"})
	 * @ParamConverter("order", class="AppBundle:CreativityOrder", options={"id" = "creativity_order_id"})
	 * @Method({"GET", "POST"})
	 * @Security("has_role('ROLE_CLIENT')")
	 * @return Response
	 */
	public function completedCustomCreativityAction(Request $request, Creativity $creativity, CreativityOrder $order)
	{
		/** @var Client $client */
		$client = $this->container->get('security.token_storage')->getToken()->getUser();
		if($order->getClient() !== $client) {
			$this->get('session')->getFlashBag()->add('success', $this->get('translator')->trans('creativity_order.permissions_denied'));

			return $this->redirectToRoute('admin_creativity_selection');
		}

		if($creativity->getSupport() === Creativity::SUPPORT_FLYERS) {
			$form = $this->createForm(new CreativityOrderDeliveryType(), $order);
			$form->add('submit', 'Symfony\Component\Form\Extension\Core\Type\SubmitType', array('label' => $this->get('translator')->trans('app.create_btn'),'attr'=>array('class'=>'btn btn-success')));
			$form->handleRequest($request);

			if ($form->isSubmitted() && $form->isValid()) {
				$em = $this->getDoctrine()->getManager();
				$em->persist($order);
				$em->flush();

				$this->get('session')->getFlashBag()->add('success', $this->get('translator')->trans('creativity_order.send_notification'));

				return $this->redirectToRoute('admin_creativity_selection');
			}
		} else {
			$this->sendNotification($order);
			$this->get('session')->getFlashBag()->add('success', $this->get('translator')->trans('creativity_order.send_notification'));

			return $this->redirectToRoute('admin_creativity_selection');
		}

		return $this->render(
			'AppBundle:CreativityOrder:complete.html.twig',
			array(
				'support' => $creativity->getSupport(),
				'creativity' => $creativity,
				'entity' => $order,
				'latitude' => $client->getLatitude(),
				'longitude' => $client->getLongitude(),
				'form' => $form->createView(),
				'breadcrumbs' => $this->getBreadCrumbs(false),
				'active_side_bar' => $this->getActiveSidebar()
			)
		);
	}

	private function sendNotification(CreativityOrder $creativityOrder)
	{
		//Send Email
		$this->get('event_dispatcher')->dispatch(
			OrderEvents::ORDER_CREATED,
			new OrderEvent($creativityOrder)
		);
	}

	/**
	 * @param Request    $request
	 * @param Creativity $creativity
	 * @param Client     $client
	 *
	 * @Route("/creativity/{id}/client/{client_id}/create-pdf", name="admin_creativity_create_pdf")
	 * @ParamConverter("creativity", class="AppBundle:Creativity", options={"id" = "id"})
	 * @ParamConverter("client", class="AppBundle:Client", options={"id" = "client_id"})
	 * @Method({"POST"})
	 * @Security("has_role('ROLE_CLIENT')")
	 * @return Response
	 */
	public function createPdfPreviewAction(Request $request, Creativity $creativity, Client $client)
	{
		$supportType = $creativity->getSupport();
		$supportBackgroundImages = $this->getSupportBackgroundImagesPathByCreativity($creativity);
		$contentValues = $request->request->get('values');
		$bgImageAttributes = ImageHandler::getImageSize($supportBackgroundImages[0]);
		$logoPath = ltrim($this->container->getParameter('app.path.images'), '/') . '/' . $client->getLogo();

		return Pdf::generatePdf($supportType, $contentValues, $supportBackgroundImages, $bgImageAttributes, $logoPath);
	}

	private function createCompletedPdf(CreativityOrder $creativityOrder, Creativity $creativity, Client $client, $filename = null)
	{
		$supportType = $creativity->getSupport();
		$supportBackgroundImages = $this->getSupportBackgroundImagesPathByCreativity($creativity);
		$clientLogo = $client->getLogo();
		$contentValues = $creativityOrder->getFieldsValue();
		$bgImageAttributes = ImageHandler::getImageSize($supportBackgroundImages[0]);
		$logoPath = ltrim($this->container->getParameter('app.path.images'), '/') . '/' . $clientLogo;

		return Pdf::generatePdf($supportType, $contentValues, $supportBackgroundImages, $bgImageAttributes, $logoPath, $filename);
	}

	private function getSupportBackgroundImagesPathByCreativity(Creativity $creativity)
	{
		$supportBackgroundImagesCollection = $creativity->getFileDocsRawOnArray();
		$supportBackgroundImages = array();
		foreach ($supportBackgroundImagesCollection as $item) {
			$supportBackgroundImages[] = ltrim($this->container->getParameter('app.path.creativities_raw'), '/') . '/' . $item;
		}

		return $supportBackgroundImages;
	}
}