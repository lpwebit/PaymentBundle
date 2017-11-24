<?php
/**
 * Created by PhpStorm.
 * User: lpezzali
 * Date: 28/12/15
 * Time: 14:27
 */

namespace LpWeb\PaymentBundle\Util\PayPal;


use Doctrine\ORM\EntityManager;
use LpWeb\PaymentBundle\Entity\PayPalIpnLog;
use LpWeb\PaymentBundle\Entity\PayPalRequest;
use LpWeb\PaymentBundle\Entity\PaymentRequestItem;
use LpWeb\PaymentBundle\Event\PayPalNotifyEvent;
use LpWeb\PaymentBundle\LpWebPaymentEvents;
use Symfony\Component\DependencyInjection\Container;
use Symfony\Component\HttpFoundation\Request;

class PayPalWPS extends PaymentInterface {

	const paymentMethod = 'paypal_wps';

	// Configuration field
	private $businessMail;
	private $items = [];
	private $data = [];

	function __construct(Container $container) {
		$parameters = $container->getParameter('lp_web_payment');
		parent::__construct($container, $parameters);

		$this->businessMail = $parameters['paypal']['business_mail'];
	}

	/**
	 * @return mixed
	 */
	public function getBusinessMail() {
		return $this->businessMail;
	}

	/**
	 * @param mixed $businessMail
	 */
	public function setBusinessMail($businessMail) {
		$this->businessMail = $businessMail;
	}

	public function getDefaultData() {
		// Specify the checkout experience to present to the user.
		$defaultData['cmd'] = '_cart';

		// Signify we're passing in a shopping cart from our system.
		$defaultData['upload'] = '1';

		// The store's PayPal e-mail address
		$defaultData['business'] = $this->getBusinessMail();

		// The application generating the API request
		$defaultData['bn'] = 'LpWebPayment_Bundle_WPS';

		// Set the correct character set
		$defaultData['charset'] = 'utf-8';

		// Do not display a comments prompt at PayPal
		$defaultData['no_note'] = '1';

		// Do not display a shipping address prompt at PayPal
		$defaultData['no_shipping'] = '1';

		// Return to the review page when payment is canceled
		$defaultData['cancel_return'] = $this->getCancelUrl($this::paymentMethod);

		// Return to the payment redirect page for processing successful payments
		$defaultData['return'] = $this->getSuccessUrl($this::paymentMethod);

		// The path PayPal should send the IPN to
		$defaultData['notify_url'] = $this->getNotifyUrl($this::paymentMethod);

		$defaultData['rm'] = '2';

		// The type of payment action PayPal should take with this order
		$defaultData['paymentaction'] = $this::ACTION;

		// Set the currency and language codes
		$defaultData['currency_code'] = 'AUD';
		$defaultData['lc'] = 'EN';

		// Use the timestamp to generate a unique invoice number
		$defaultData['invoice'] = $this->getInvoiceNumber();

		return $defaultData;
	}

	public function configure(array $data) {
		// invoice number, amount and description is compulsory
		$this->validateData();

		$defaultData = $this->getDefaultData();

		$this->data = array_merge($defaultData, $this->data, $data);
	}

	public function addItem($description, $amount) {
		$this->items[] = [
			'description' => $description,
			'amount' => $amount
		];
	}

	public function process() {
		if (count($this->items) <= 0) {
			throw new \Exception("You can't proceed without item in cart, consider adding at least one");
		}

		$this->configure(['return_url' => $this->getRedirectUrl()]);

		$paypalRequest = new PayPalRequest();
		$paypalRequest->setUniqueId($this->getUniqueId());
		$paypalRequest->setCmd($this->data['cmd']);
		$paypalRequest->setUpload($this->data['upload']);
		$paypalRequest->setBusiness($this->data['business']);
		$paypalRequest->setBn($this->data['bn']);
		$paypalRequest->setCharset($this->data['charset']);
		$paypalRequest->setNoNote($this->data['no_note']);
		$paypalRequest->setNoShipping($this->data['no_shipping']);
		$paypalRequest->setCancelReturn($this->data['cancel_return']);
		$paypalRequest->setReturnUrl($this->data['return_url']);
		$paypalRequest->setNotifyUrl($this->data['notify_url']);
		$paypalRequest->setRm($this->data['rm']);
		$paypalRequest->setPaymentaction($this->data['paymentaction']);
		$paypalRequest->setCurrencyCode($this->data['currency_code']);
		$paypalRequest->setLc($this->data['lc']);
		$paypalRequest->setInvoice($this->data['invoice']);

		$total = 0;
		$i = 1;
		$paypalRequestItems = [];
		foreach ($this->items as $item) {
			$this->data['item_name_' . $i] = $item['description'];
			$this->data['amount_' . $i] = (double)$item['amount'];
			$total += $this->data['amount_' . $i];
			$paypalRequestItem = new PaymentRequestItem();
			$paypalRequestItem->setAmount($item['amount']);
			$paypalRequestItem->setDescription($item['description']);
			$paypalRequestItem->setRequest($paypalRequest);
			$paypalRequestItems[] = $paypalRequestItem;
			$i++;
		}

		if($total > $this->getAmount()->getTotal()) {
			throw new \Exception("The total amount of the items is more than the order total");
		}



		/** @var EntityManager $em */
		$em = $this->container->get('doctrine')->getManager();
		$em->transactional(function (EntityManager $em) use ($paypalRequest, $paypalRequestItems) {
			$em->persist($paypalRequest);
			foreach ($paypalRequestItems as $paypalRequestItem) {
				$em->persist($paypalRequestItem);
			}
		});

		return $this->container->get('templating')->renderResponse('LpWebPaymentBundle:Payment:process.html.twig', ['data' => ['serverUrl' => $this->getServerUrl(), 'data' => $this->data]]);
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

		try {
			/** @var EntityManager $em */
			$em = $this->container->get('doctrine')->getManager();
			$em->transactional(function (EntityManager $em) use ($ipnLog) {
				$em->persist($ipnLog);
			});
		} catch (\Exception $ex) {
			$this->container->get('logger')->error($ex);
		}

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
