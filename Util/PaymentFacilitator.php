<?php

namespace LpWeb\PaymentBundle\Util;

use LpWeb\PaymentBundle\Entity\PaymentRequest;
use LpWeb\PaymentBundle\Entity\PaymentRequestItem;

/**
 * Created by PhpStorm.
 * User: lpezzali
 * Date: 08/08/16
 * Time: 23:02
 */
class PaymentFacilitator {

	const PaymentTypePayPalWPS = "lpweb_payment.paypal.wps";
	const PaymentTypePayPalExpressCheckout = "lpweb_payment.paypal.express_checkout";
	private $acceptedMethod = [PaymentFacilitator::PaymentTypePayPalWPS, PaymentFacilitator::PaymentTypePayPalExpressCheckout];

	/** @var PaymentRequest */
	private $paymentRequest;
	/** @var PaymentRequestItem[] */
	private $paymentRequestItems = [];

	function __construct() {
		$this->paymentRequest = new PaymentRequest();
		$this->paymentRequest->setUniqueId($this->generateUniqueId());
		dump($this->paymentRequest->getUniqueId()); die;
	}

	/**
	 * Generate a pseudo random unique id for the transaction
	 * @return int the transaction unique id
	 */
	private function generateUniqueId() {
		return random_int(10000000000, 99999999999);
	}

	/**
	 * Add an item to the list
	 * @param $description
	 * @param $amount
	 * @param int $quantity
	 * @param string $sku
	 * @throws \Exception
	 */
	public function addItem($description, $amount, $quantity = 1, $sku = '') {
		$this->checkAmount($amount);
		$item = new PaymentRequestItem();
		$item->setDescription($description);
		$item->setAmount($amount);
		$item->setQuantity($quantity);
		$item->setSku($sku);
		$item->setRequest($this->paymentRequest);
		$this->paymentRequestItems[] = $item;
	}

	/**
	 * @param $amount
	 * @throws \Exception in case the amount is not numeric
	 */
	private function checkAmount($amount) {
		if(!is_numeric($amount)) {
			throw new \Exception("The amount should be a valid number");
		}
	}

	/**
	 * @return \LpWeb\PaymentBundle\Entity\PaymentRequestItem[]
	 */
	public function getPaymentRequestItems() {
		return $this->paymentRequestItems;
	}

	public function setPaymentMethod($method) {
		if(!in_array($method, $this->acceptedMethod)) {
			throw new \Exception("Payment method not valid");
		}
		$this->paymentRequest->setPaymentMethod($method);
	}

	public function processPayment() {
		if(empty($this->paymentRequest->getPaymentMethod())) {
			throw new \Exception("Payment method not set. You must choose a method before process the payment");
		}
		switch($this->paymentRequest->getPaymentMethod()) {
			case PaymentFacilitator::PaymentTypePayPalWPS:
				break;
			case PaymentFacilitator::PaymentTypePayPalExpressCheckout:
				break;
			default:

		}
	}

}