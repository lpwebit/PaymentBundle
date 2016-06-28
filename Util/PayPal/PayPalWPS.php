<?php
/**
 * Created by PhpStorm.
 * User: lpezzali
 * Date: 28/12/15
 * Time: 14:27
 */

namespace LpWeb\PaymentBundle\Util\PayPal;


use LpWeb\PaymentBundle\Entity\PayPalIpnLog;
use LpWeb\PaymentBundle\Entity\PayPalRequest;
use LpWeb\PaymentBundle\Event\PayPalNotifyEvent;
use LpWeb\PaymentBundle\LpWebPaymentEvents;
use Symfony\Component\DependencyInjection\Container;
use Symfony\Component\HttpFoundation\Request;

class PayPalWPS extends PaymentInterface {

	const paymentMethod = 'paypal_wps';

	// Configuration field
	private $businessMail;

	function __construct(Container $container) {
		$parameters = $container->getParameter('lp_web_payment');
		parent::__construct($container, $parameters);

		$this->businessMail = $parameters['paypal']['business_mail'];
	}


	public function getData($customData = []) {
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

		$paypalRequest = new PayPalRequest();
		$paypalRequest->setUniqueId($this->getUniqueId());
		$paypalRequest->setCmd($data['cmd']);
		$paypalRequest->setUpload($data['upload']);
		$paypalRequest->setBusiness($data['business']);
		$paypalRequest->setBn($data['bn']);
		$paypalRequest->setCharset($data['charset']);
		$paypalRequest->setNoNote($data['no_note']);
		$paypalRequest->setNoShipping($data['no_shipping']);
		$paypalRequest->setCancelReturn($data['cancel_return']);
		$paypalRequest->setReturn($data['return']);
		$paypalRequest->setNotifyUrl($data['notify_url']);
		$paypalRequest->setRm($data['rm']);
		$paypalRequest->setPaymentaction($data['paymentaction']);
		$paypalRequest->setCurrencyCode($data['currency_code']);
		$paypalRequest->setLc($data['lc']);
		$paypalRequest->setInvoice($data['invoice']);
		$paypalRequest->setAmount1($data['amount_1']);
		$paypalRequest->setItemName1($data['item_name_1']);
		$paypalRequest->setCustomData(serialize($customData));

		$em = $this->container->get('doctrine')->getManager();
		$em->persist($paypalRequest);
		$em->flush();

		return [
			'uniqueId' => $this->getUniqueId(),
			'serverUrl' => $this->getServerUrl(),
			'data' => $data
		];
	}

	public function notify(Request $request, $uniqueId) {
		$ipnLog = new PayPalIpnLog();

		$ipnLog->setUniqueId($uniqueId);
		$ipnLog->setMcGross($request->get('mc_gross'));
		$ipnLog->setInvoice($request->get('invoice'));
		$ipnLog->setProtectionEligibility($request->get('protection_eligibility'));
		$ipnLog->setItemNumber1($request->get('item_number1'));
		$ipnLog->setPayerId($request->get('payer_id'));
		$ipnLog->setTax($request->get('tax'));
		$ipnLog->setPaymentDate($request->get('payment_date'));
		$ipnLog->setPaymentStatus($request->get('payment_status'));
		$ipnLog->setCharset($request->get('charset'));
		$ipnLog->setMcShipping($request->get('mc_shipping'));
		$ipnLog->setMcHandling($request->get('mc_handling'));
		$ipnLog->setFirstName($request->get('first_name'));
		$ipnLog->setMcFee($request->get('mc_fee'));
		$ipnLog->setNotifyVersion($request->get('notify_version'));
		$ipnLog->setCustom($request->get('custom'));
		$ipnLog->setPayerStatus($request->get('payer_status'));
		$ipnLog->setBusiness($request->get('business'));
		$ipnLog->setNumCartItems($request->get('num_cart_items'));
		$ipnLog->setMcHandling1($request->get('mc_handling1'));
		$ipnLog->setPayerEmail($request->get('payer_email'));
		$ipnLog->setVerifySign($request->get('verify_sign'));
		$ipnLog->setMcShipping1($request->get('mc_shipping1'));
		$ipnLog->setTax1($request->get('tax1'));
		$ipnLog->setTxnId($request->get('txn_id'));
		$ipnLog->setPaymentType($request->get('payment_type'));
		$ipnLog->setLastName($request->get('last_name'));
		$ipnLog->setItemName1($request->get('item_name1'));
		$ipnLog->setReceiverEmail($request->get('receiver_email'));
		$ipnLog->setPaymentFee($request->get('payment_fee'));
		$ipnLog->setQuantity1($request->get('quantity1'));
		$ipnLog->setReceiverId($request->get('receiver_id'));
		$ipnLog->setTxnType($request->get('txn_type'));
		$ipnLog->setMcGross1($request->get('mc_gross_1'));
		$ipnLog->setMcCurrency($request->get('mc_currency'));
		$ipnLog->setResidenceCountry($request->get('residence_country'));
		$ipnLog->setTestIpn($request->get('test_ipn'));
		$ipnLog->setTransactionSubject($request->get('transaction_subject'));
		$ipnLog->setPaymentGross($request->get('payment_gross'));
		$ipnLog->setAuth($request->get('auth'));

		$em = $this->container->get('doctrine')->getManager();
		$em->persist($ipnLog);
		$em->flush();

		/** @var $dispatcher \Symfony\Component\EventDispatcher\EventDispatcherInterface */
		$dispatcher = $this->container->get('event_dispatcher');

		$dispatcher->dispatch(LpWebPaymentEvents::NOTIFY_PAYPAL_PAYMENT_IPN, new PayPalNotifyEvent($ipnLog));
	}

	public function cancel($uniqueId) {
		// TODO: Implement cancel() method.
	}

	public function success(Request $request, $uniqueId) {
		return $request->get('redirectUrl');
	}
}
