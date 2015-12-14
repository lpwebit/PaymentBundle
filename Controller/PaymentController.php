<?php

namespace LpWeb\PaymentBundle\Controller;

use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Template;
use PayPal\Api\Amount;
use PayPal\Api\Details;
use PayPal\Api\Item;
use PayPal\Api\ItemList;
use PayPal\Api\Payer;
use PayPal\Api\Payment;
use PayPal\Api\RedirectUrls;
use PayPal\Api\Transaction;
use PayPal\Auth\OAuthTokenCredential;
use PayPal\Rest\ApiContext;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\Routing\Generator\UrlGeneratorInterface;

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
	 * @Route("/success", name="payment_success")
	 * @Template()
	 */
	public function successAction() {
		return [];
	}

	/**
	 * @Route("/cancel", name="payment_cancel")
	 * @Template()
	 */
	public function cancelAction() {
		return [];
	}

	/**
	 * @Route("/notify", name="payment_notify")
	 */
	public function notifyAction() {
		return [];
	}

}
