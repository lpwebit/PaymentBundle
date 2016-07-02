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

	/**
	 * @return mixed
	 */
	public function getBusinessMail()
	{
		return $this->businessMail;
	}

	/**
	 * @param mixed $businessMail
	 */
	public function setBusinessMail($businessMail)
	{
		$this->businessMail = $businessMail;
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
