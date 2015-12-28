<?php
/**
 * Created by PhpStorm.
 * User: lpezzali
 * Date: 12/12/15
 * Time: 15:51
 */

namespace LpWeb\PaymentBundle\Util\PayPal;


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

/**
 * Class PayPalExpressCheckout
 * @package LpWeb\PaymentBundle\Util\PayPal
 * Not completed yet
 */
class PayPalExpressCheckout extends PaymentInterface {

	const paymentMethod = 'paypal_express_checkout';

	// Configuration field
	private $clientId;
	private $clientSecret;

	// Transactional field
	/** @var ItemList */
	private $itemList;
	/** @var null|Details */
	private $details = null;

	function __construct(Container $container) {
		$parameters = $container->getParameter('lp_web_payment');
		parent::__construct($container, $parameters);

		$this->clientId = $parameters['paypal']['client_id'];
		$this->clientSecret = $parameters['paypal']['client_secret'];

		$this->itemList = new ItemList();
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
			->setCurrency($this->getCurrency())
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
	 * Create the Payment request and process
	 * @return null|string The approval url to which the user has to be redirected
	 */
	public function createPayment() {
		$payer = new Payer();
		$payer->setPaymentMethod("paypal");

		$this->validateData();

		if (!is_null($this->details)) {
			$this->getAmount()->setDetails($this->details);
		}

		$transaction = new Transaction();
		$transaction->setAmount($this->getAmount())
			->setDescription($this->getDescription())
			->setInvoiceNumber($this->getInvoiceNumber());

		if(count($this->itemList->getItems())) {
			$transaction->setItemList($this->itemList);
		}

		$redirectUrls = new RedirectUrls();
		$redirectUrls->setReturnUrl($this->getSuccessUrl(self::paymentMethod))
			->setCancelUrl($this->getCancelUrl(self::paymentMethod));

		$payment = new Payment();
		$payment->setIntent(self::ACTION)
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



	private function getApiContext($clientId, $clientSecret) {
		$apiContext = new ApiContext(
			new OAuthTokenCredential(
				$clientId,
				$clientSecret
			)
		);

		$apiContext->setConfig(
			array(
				'mode' => $this->getMode(),
				'log.LogEnabled' => true,
				'log.FileName' => $this->getLogFilePath(),
				'log.LogLevel' => $this->getLogLevel(), // PLEASE USE `FINE` LEVEL FOR LOGGING IN LIVE ENVIRONMENTS
				'cache.enabled' => true,
				// 'http.CURLOPT_CONNECTTIMEOUT' => 30
				// 'http.headers.PayPal-Partner-Attribution-Id' => '123123123'
			)
		);

		return $apiContext;
	}

}