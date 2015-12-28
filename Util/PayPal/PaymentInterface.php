<?php

namespace LpWeb\PaymentBundle\Util\PayPal;


use PayPal\Api\Amount;
use Symfony\Component\DependencyInjection\Container;
use Symfony\Component\DependencyInjection\ContainerAware;
use Symfony\Component\Routing\Generator\UrlGeneratorInterface;

/**
 * Created by PhpStorm.
 * User: lpezzali
 * Date: 28/12/15
 * Time: 14:32
 */
abstract class PaymentInterface extends ContainerAware {

	const ACTION = 'sale';

	function __construct(Container $container, $parameters) {
		parent::setContainer($container);

		$this->mode = $parameters['paypal']['mode'];
		$this->logLevel = $parameters['paypal']['logLevel'];
	}

	// Configuration field
	/**
	 * @var string Currency constant
	 * @see PayPalCurrency
	 */
	private $currency = PayPalCurrency::CURRENCY_EUR;
	private $logLevel;
	private $mode;

	/**
	 * @return string
	 */
	public function getCurrency() {
		return $this->currency;
	}

	// Transactional field
	/** @var string|null */
	private $invoiceNumber;
	/** @var string|null */
	private $cancelUrl;
	/** @var string|null */
	private $successUrl;
	/** @var Amount */
	private $amount;
	/** @var string */
	private $description;

	/**
	 * @return null|string
	 */
	public function getInvoiceNumber() {
		return $this->invoiceNumber;
	}

	/**
	 * @return null|string
	 */
	public function getUniqueInvoiceNumber() {
		return $this->invoiceNumber . '-' . time();
	}

	/**
	 * @param null|string $invoiceNumber
	 */
	public function setInvoiceNumber($invoiceNumber) {
		$this->invoiceNumber = $invoiceNumber;
	}

	/**
	 * @return null|string
	 */
	public function getCancelUrl($paymentMethod) {
		if(!empty($this->cancelUrl)) {
			return $this->cancelUrl;
		}
		return $this->getAbsoluteUrl('payment_cancel', $paymentMethod);
	}

	/**
	 * @param null|string $cancelUrl
	 */
	public function setCancelUrl($cancelUrl) {
		$this->cancelUrl = $cancelUrl;
	}

	/**
	 * @return null|string
	 */
	public function getSuccessUrl($paymentMethod) {
		if(!empty($this->successUrl)) {
			return $this->successUrl;
		}
		return $this->getAbsoluteUrl('payment_success', $paymentMethod);
	}

	/**
	 * @param null|string $successUrl
	 */
	public function setSuccessUrl($successUrl) {
		$this->successUrl = $successUrl;
	}

	/**
	 * @return null|string
	 */
	public function getNotifyUrl($paymentMethod) {
		return $this->getAbsoluteUrl('payment_notify', $paymentMethod);
	}

	/**
	 * @return Amount
	 */
	public function getAmount() {
		return $this->amount;
	}

	/**
	 * Set the total amount of the order, this is the price the user is going to pay
	 * @param float $total Total amount of the order
	 */
	public function setAmout($total) {
		$this->amount = new Amount();
		$this->amount->setCurrency($this->getCurrency())
			->setTotal($total);
	}

	/**
	 * @return mixed
	 */
	public function getLogLevel() {
		return $this->logLevel;
	}

	/**
	 * @return mixed
	 */
	public function getMode() {
		return $this->mode;
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

	private function getAbsoluteUrl($routeName, $paymentMethod) {
		return $this->container->get('router')->generate($routeName, ['paymentMethod' => $paymentMethod], UrlGeneratorInterface::ABSOLUTE_URL);
	}

	protected function getLogFilePath() {
		return $this->container->get('kernel')->getLogDir() . '/PayPal_' . $this->mode . '.log';
	}

	protected function validateData() {
		if(!$this->invoiceNumber) {
			throw new \Exception("invoiceNumber not configured");
		}
		if(!$this->description) {
			throw new \Exception("Payment description not configured");
		}
		if(!$this->amount) {
			throw new \Exception("Payment Amout not configured");
		}
	}

	protected function getServerUrl() {
		switch ($this->mode) {
			case 'sandbox':
				return 'https://www.sandbox.paypal.com/cgi-bin/webscr';
			case 'live':
				return 'https://www.paypal.com/cgi-bin/webscr';
			default:
				return '';
		}
	}

}