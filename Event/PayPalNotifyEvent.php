<?php

namespace LpWeb\PaymentBundle\Event;

use LpWeb\PaymentBundle\Entity\PayPalIpnLog;
use Symfony\Component\EventDispatcher\Event;

class PayPalNotifyEvent extends Event {

	/** @var PayPalIpnLog */
	private $ipnLog;

	/**
	 * PayPalNotifyEvent constructor.
	 * @param $ipnLog
	 */
	public function __construct(PayPalIpnLog $ipnLog) { $this->ipnLog = $ipnLog; }

	/**
	 * @return PayPalIpnLog
	 */
	public function getIpnLog() {
		return $this->ipnLog;
	}

	/**
	 * @param PayPalIpnLog $ipnLog
	 */
	public function setIpnLog(PayPalIpnLog $ipnLog) {
		$this->ipnLog = $ipnLog;
	}


}