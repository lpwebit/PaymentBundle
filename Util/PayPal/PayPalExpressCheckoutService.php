<?php
/**
 * Created by PhpStorm.
 * User: lpezzali
 * Date: 12/12/15
 * Time: 15:51
 */

namespace LpWeb\PaymentBundle\Util\PayPal;


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
use Symfony\Component\DependencyInjection\Container;
use Symfony\Component\DependencyInjection\ContainerAware;
use Symfony\Component\Routing\Generator\UrlGeneratorInterface;

class PayPalExpressCheckoutService extends ContainerAware {

	// Configuration field
	/**
	 * @var string Currency constant
	 * @see PayPalCurrency
	 */
	private $currency = PayPalCurrency::CURRENCY_EUR;
	private $mode;
	private $clientId;
	private $clientSecret;
	private $logLevel;

	// Transactional field
	/** @var ItemList */
	private $itemList;
	/** @var null|Details */
	private $details = null;
	/** @var Amount */
	private $amount;
	private $invoiceNumber;
	private $description;
	/** @var string|null */
	private $cancelUrl;
	/** @var string|null */
	private $successUrl;

	function __construct(Container $container) {
		parent::setContainer($container);

		$parameters = $this->container->getParameter('lp_web_payment');
		$this->mode = $parameters['paypal']['mode'];
		$this->clientId = $parameters['paypal']['client_id'];
		$this->clientSecret = $parameters['paypal']['client_secret'];
		$this->logLevel = $parameters['paypal']['logLevel'];

		$this->itemList = new ItemList();
	}

	/**
	 * @return string The invoice number or order id
	 */
	public function getInvoiceNumber() {
		return $this->invoiceNumber;
	}

	/**
	 * @param string $invoiceNumber The invoice number or order id
	 */
	public function setInvoiceNumber($invoiceNumber) {
		$this->invoiceNumber = $invoiceNumber;
	}

	/**
	 * @return string The transaction description as it appears on user's account balance
	 */
	public function getDescription() {
		return $this->description;
	}

	/**
	 * @param string $description
	 */
	public function setDescription($description) {
		$this->description = $description;
	}

	/**
	 * @return string The mode used by the bundle. Can be SANDBOX or LIVE
	 */
	public function getMode() {
		return $this->mode;
	}

	/**
	 * @return string The currency used by the Bundle
	 */
	public function getCurrency() {
		return $this->currency;
	}

	/**
	 * @return string the configured log level
	 */
	public function getLogLevel() {
		return $this->logLevel;
	}

	/**
	 * @param string $successUrl Url to redirect user upon succesfull payment, must be an absolute url
	 */
	public function setCustomSuccessUrl($successUrl) {
		$this->successUrl = $successUrl;
	}

	/**
	 * @param string $cancelUrl Url to redirect user upon error payment, must be an absolute url
	 */
	public function setCustomCancelUrl($cancelUrl) {
		$this->cancelUrl = $cancelUrl;
	}

	/**
	 * Add an item detail for the order you are creating
	 * @param string $sku The internal item code
	 * @param string $name Item title
	 * @param integer $quantity Item quantity
	 * @param float $price Item total price
	 */
	public function addItem($sku, $name, $quantity, $price) {
		$item = new Item();
		$item->setName($name)
			->setCurrency($this->currency)
			->setQuantity($quantity)
			->setSku($sku)
			->setPrice($price);
		$this->itemList->addItem($item);
	}

	/**
	 * Set order detail cost if any
	 * @param float $shipping The shipping cost if any
	 * @param float $tax The tax cost if any
	 * @param float $subtotal The order subtotal
	 */
	public function setDetails($shipping, $tax, $subtotal) {
		$this->details = new Details();
		$this->details->setShipping($shipping)
			->setTax($tax)
			->setSubtotal($subtotal);
	}

	/**
	 * Set the total amount of the order, this is the price the user is going to pay
	 * @param float $total Total amount of the order
	 */
	public function setAmout($total) {
		$this->amount = new Amount();
		$this->amount->setCurrency($this->currency)
			->setTotal($total);
	}

	/**
	 * Create the Payment request and process
	 * @return null|string The approval url to which the user has to be redirected
	 */
	public function createPayment() {
		$payer = new Payer();
		$payer->setPaymentMethod("paypal");

		if(!$this->invoiceNumber) {
			throw new \Exception("invoiceNumber not configured");
		}
		if(!$this->description) {
			throw new \Exception("Payment description not configured");
		}
		if(!$this->amount) {
			throw new \Exception("Payment Amout not configured");
		}

		if (!is_null($this->details)) {
			$this->amount->setDetails($this->details);
		}

		$transaction = new Transaction();
		$transaction->setAmount($this->amount)
			->setDescription($this->description)
			->setInvoiceNumber($this->invoiceNumber);

		if(count($this->itemList->getItems())) {
			$transaction->setItemList($this->itemList);
		}

		$redirectUrls = new RedirectUrls();
		$redirectUrls->setReturnUrl($this->getSuccessUrl())
			->setCancelUrl($this->getCancelUrl());

		$payment = new Payment();
		$payment->setIntent("sale")
			->setPayer($payer)
			->setRedirectUrls($redirectUrls)
			->setTransactions(array($transaction));

		try {
			$payment->create($this->getApiContext($this->clientId, $this->clientSecret));
		} catch (\Exception $ex) {
			$this->container->get('logger')->error($ex);
			return null;
		}

		$approvalUrl = $payment->getApprovalLink();

		return $approvalUrl;
	}

	private function getSuccessUrl() {
		if(!empty($this->successUrl)) {
			return $this->successUrl;
		}
		return $this->getAbsoluteUrl('payment_success');
	}

	private function getCancelUrl() {
		if(!empty($this->cancelUrl)) {
			return $this->cancelUrl;
		}
		return $this->getAbsoluteUrl('payment_cancel');
	}

	private function getAbsoluteUrl($routeName) {
		return $this->container->get('router')->generate($routeName, [], UrlGeneratorInterface::ABSOLUTE_URL);
	}

	private function getLogFilePath() {
		return $this->container->get('kernel')->getLogDir() . '/PayPal_' . $this->mode . '.log';
	}

	private function getApiContext($clientId, $clientSecret) {
		$apiContext = new ApiContext(
			new OAuthTokenCredential(
				$clientId,
				$clientSecret
			)
		);

		$apiContext->setConfig(
			array(
				'mode' => $this->mode,
				'log.LogEnabled' => true,
				'log.FileName' => $this->getLogFilePath(),
				'log.LogLevel' => $this->logLevel, // PLEASE USE `FINE` LEVEL FOR LOGGING IN LIVE ENVIRONMENTS
				'cache.enabled' => true,
				// 'http.CURLOPT_CONNECTTIMEOUT' => 30
				// 'http.headers.PayPal-Partner-Attribution-Id' => '123123123'
			)
		);

		return $apiContext;
	}

}