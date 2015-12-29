<?php

namespace LpWeb\PaymentBundle\Util\PayPal;


use PayPal\Api\Amount;
use Symfony\Component\DependencyInjection\Container;
use Symfony\Component\DependencyInjection\ContainerAware;
use Symfony\Component\HttpFoundation\Request;
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
	/** @var string */
	private $uniqueId;
	/** @var string|null */
	private $invoiceNumber;
	/** @var Amount */
	private $amount;
	/** @var string */
	private $description;
	/** @var string */
	private $redirectUrl;

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
		$this->setUniqueId(md5($invoiceNumber.time()));
	}

	/**
	 * @return null|string
	 */
	public function getSuccessUrl($paymentMethod) {
		return $this->getAbsoluteUrl('payment_success', $paymentMethod, ['uniqueId' => $this->getUniqueId(), 'redirectUrl' => $this->getRedirectUrl()]);
	}

	/**
	 * @return null|string
	 */
	public function getCancelUrl($paymentMethod) {
		return $this->getAbsoluteUrl('payment_cancel', $paymentMethod, ['uniqueId' => $this->getUniqueId()]);
	}

	/**
	 * @return null|string
	 */
	public function getNotifyUrl($paymentMethod) {
		return $this->getAbsoluteUrl('payment_notify', $paymentMethod, ['uniqueId' => $this->getUniqueId()]);
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

	/**
	 * @return string
	 */
	public function getRedirectUrl() {
		return $this->redirectUrl;
	}

	/**
	 * @param string $redirectUrl
	 */
	public function setRedirectUrl($redirectUrl) {
		$this->redirectUrl = $redirectUrl;
	}

	/**
	 * @return string
	 */
	public function getUniqueId() {
		return $this->uniqueId;
	}

	/**
	 * @param string $uniqueId
	 * @return PaymentInterface
	 */
	public function setUniqueId($uniqueId) {
		$this->uniqueId = $uniqueId;
		return $this;
	}

	private function getAbsoluteUrl($routeName, $paymentMethod, $params = []) {
		$params += ['paymentMethod' => $paymentMethod];
		return $this->container->get('router')->generate($routeName, $params, UrlGeneratorInterface::ABSOLUTE_URL);
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

	public abstract function notify(Request $request, $uniqueId);

	public abstract function success(Request $request, $uniqueId);

	public abstract function cancel($uniqueId);

}