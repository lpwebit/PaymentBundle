<?php

namespace LpWeb\PaymentBundle\Event;

use LpWeb\PaymentBundle\Entity\PayPalIpnLog;
use Symfony\Component\EventDispatcher\Event;

class PayPalNotifyEvent extends Event {

	private $ipnLog;

	/**
	 * PayPalNotifyEvent constructor.
	 * @param $ipnLog
	 */
	public function __construct(PayPalIpnLog $ipnLog) { $this->ipnLog = $ipnLog; }

	/**
	 * @return mixed
	 */
	public function getIpnLog() {
		return $this->ipnLog;
	}

	/**
	 * @param mixed $ipnLog
	 */
	public function setIpnLog($ipnLog) {
		$this->ipnLog = $ipnLog;
	}


}