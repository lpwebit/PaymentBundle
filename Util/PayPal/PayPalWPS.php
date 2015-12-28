<?php
/**
 * Created by PhpStorm.
 * User: lpezzali
 * Date: 28/12/15
 * Time: 14:27
 */

namespace LpWeb\PaymentBundle\Util\PayPal;


use LpWeb\PaymentBundle\Form\PayPalWPSType;
use Symfony\Component\DependencyInjection\Container;

class PayPalWPS extends PaymentInterface {

	const paymentMethod = 'paypal_wps';

	// Configuration field
	private $businessMail;

	function __construct(Container $container) {
		$parameters = $container->getParameter('lp_web_payment');
		parent::__construct($container, $parameters);

		$this->businessMail = $parameters['paypal']['business_mail'];
	}

	public function process() {
		$this->validateData();

		// Specify the checkout experience to present to the user.
		$data['cmd'] = '_cart';

		// Signify we're passing in a shopping cart from our system.
		$data['upload'] = '1';

		// The store's PayPal e-mail address
		$data['business'] = $this->businessMail;

		// The application generating the API request
		$data['bn'] = 'LpWebPayment_Bundle_WPS';

		// Set the correct character set
		$data['charset'] = 'utf-8';

		// Do not display a comments prompt at PayPal
		$data['no_note'] = '1';

		// Do not display a shipping address prompt at PayPal
		$data['no_shipping'] = '1';

		// Return to the review page when payment is canceled
		$data['cancel_return'] = $this->getCancelUrl(self::paymentMethod);

		// Return to the payment redirect page for processing successful payments
		$data['return'] = $this->getSuccessUrl(self::paymentMethod);

		// The path PayPal should send the IPN to
		$data['notify_url'] = $this->getNotifyUrl(self::paymentMethod);

//		$data['notify_url'] = 'http://dev.learn.serilab.eu/commerce_paypal/ipn/paypal_wps%7Ccommerce_payment_paypal_wps';
//		$data['cancel_return'] = 'http://dev.learn.serilab.eu/checkout/17/payment/back/4gomqgCFUYucH3kSWw0HdxDQ0nWZTCloFSx63xwSBGM';
//		$data['return'] = 'http://dev.learn.serilab.eu/checkout/17/payment/return/4gomqgCFUYucH3kSWw0HdxDQ0nWZTCloFSx63xwSBGM';

		// Return to this site with payment data in the POST
		$data['rm'] = '2';

		// The type of payment action PayPal should take with this order
		$data['paymentaction'] = self::ACTION;

		// Set the currency and language codes
		$data['currency_code'] = $this->getCurrency();
		$data['lc'] = 'IT';

		// Use the timestamp to generate a unique invoice number
		$data['invoice'] = $this->getInvoiceNumber();

		// Define a single item in the cart representing the whole order
		$data['amount_1'] = $this->getAmount()->getTotal();
		$data['item_name_1'] = $this->getDescription();
//		$data['on0_1'] = 'Product count';
//		$data['os0_1'] = '2';

		return [
			'serverUrl' => $this->getServerUrl(),
			'data' => $data
		];
	}
}