<?php

namespace LpWeb\PaymentBundle\Controller;

use LpWeb\PaymentBundle\Util\PayPal\PaymentInterface;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Template;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;

/**
 * @Route("/payment")
 */
class PaymentController extends Controller {

	/**
	 * @Route("/process", name="payment_process")
	 * @Template()
	 */
	public function processAction() {
		return [];
	}

	/**
	 * @Route("/success/{uniqueId}/{paymentMethod}", name="payment_success")
	 * @Template()
	 */
	public function successAction(Request $request, $paymentMethod, $uniqueId) {
		/** @var PaymentInterface $paymentService */
		$paymentService = $this->getPaymentService($paymentMethod);

		return $this->redirect($paymentService->success($request, $uniqueId));
	}

	/**
	 * @Route("/cancel/{uniqueId}/{paymentMethod}", name="payment_cancel")
	 * @Template()
	 */
	public function cancelAction(Request $request, $paymentMethod, $uniqueId) {
		/** @var PaymentInterface $paymentService */
		$paymentService = $this->getPaymentService($paymentMethod);
		$paymentService->cancel($uniqueId);

		return [];
	}

	/**
	 * @Route("/notify/{uniqueId}/{paymentMethod}", name="payment_notify")
	 */
	public function notifyAction(Request $request, $paymentMethod, $uniqueId) {
		/** @var PaymentInterface $paymentService */
		$paymentService = $this->getPaymentService($paymentMethod);
		$paymentService->notify($request, $uniqueId);

		return new Response();
	}

	/**
	 * @Route("/view-notify/", name="view_payment_notify")
	 */
	public function viewNotifyAction(Request $request) {
		return [];
	}

	private function getPaymentService($paymentMethod) {
		$method = explode('_', $paymentMethod);
		unset($method[0]);
		$method = implode('_', $method);
		return $this->get('lpweb_payment.paypal.' . $method);
	}

}
