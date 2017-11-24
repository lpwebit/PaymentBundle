<?php

namespace LpWeb\PaymentBundle\Controller;

use LpWeb\PaymentBundle\Entity\PayPalRequest;
use LpWeb\PaymentBundle\Util\PaymentFacilitator;
use LpWeb\PaymentBundle\Util\PayPal\PaymentInterface;
use LpWeb\PaymentBundle\Util\PayPal\PayPalWPS;
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
	 * @Route("/test", name="test")
	 */
	public function testAction() {
		$f = new PaymentFacilitator();
		/** @var PayPalWPS $wpsService */
		$wpsService = $this->get('lpweb_payment.paypal.wps');
		$wpsService->setAmout(44.0);
		$wpsService->setDescription('Transaction Description');
		$wpsService->setInvoiceNumber(1);
		// set the redirect url to which the user has to be redirected after successfull completion
		$wpsService->setRedirectUrl($this->generateUrl('payment_success'));

		$wpsService->addItem('Prova 1', 10);
		$wpsService->addItem('Articolo 2', 34);

		return $wpsService->process();
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
