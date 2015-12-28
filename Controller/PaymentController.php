<?php

namespace LpWeb\PaymentBundle\Controller;

use LpWeb\PaymentBundle\Entity\PayPalIpnLog;
use PayPal\Api\PaymentExecution;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Template;
use PayPal\Api\Payment;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;

/**
 * @Route("/payment")
 */
class PaymentController extends Controller {

	/**
	 * @Route("/process", name="payment_process")
	 * @Template()
	 */
	public function processAction() {
		$wpsService = $this->get('lpweb_payment.paypal.wps');
		$wpsService->setAmout(10.0);
		$wpsService->setDescription('NearToAll Registrazione');
		$wpsService->setInvoiceNumber(6);
		$data = $wpsService->process();

		return [
			'data' => $data
		];
	}

	/**
	 * @Route("/success/{paymentMethod}", name="payment_success")
	 * @Template()
	 */
	public function successAction(Request $request, $paymentMethod) {
		dump($_POST); die;
		$paymentService = $this->getPaymentService($paymentMethod);
//		$paymentId = $request->get('paymentId');
//		$payment = Payment::get($paymentId, $apiContext);
//
//		$execution = new PaymentExecution();
//		$execution->setPayerId($request->get('PayerID'));

		return [];
	}

	/**
	 * @Route("/cancel/{paymentMethod}", name="payment_cancel")
	 * @Template()
	 */
	public function cancelAction(Request $request, $paymentMethod) {
		$paymentService = $this->getPaymentService($paymentMethod);
		return [];
	}

	/**
	 * @Route("/notify/{paymentMethod}", name="payment_notify")
	 */
	public function notifyAction(Request $request, $paymentMethod) {
		$paymentService = $this->getPaymentService($paymentMethod);
		$ipnLog = new PayPalIpnLog();
		$ipnLog->setRequest(serialize($_POST));
		$em = $this->getDoctrine()->getManager();
		$em->persist($ipnLog);
		$em->flush();
		return [];
	}

	/**
	 * @Route("/view-notify/", name="view_payment_notify")
	 */
	public function viewNotifyAction(Request $request, $paymentMethod) {
		$em = $this->getDoctrine()->getManager();
		$result = $em->getRepository('LpWebPaymentBundle:PayPalIpnLog')->findAll();
		dump($result[0]); die;
		return [];
	}

	private function getPaymentService($paymentMethod) {
		$method = explode('_', $paymentMethod);
		unset($method[0]);
		$method = implode('_', $method);
		return $this->get('lpweb_payment.paypal.' . $method);
	}

}
