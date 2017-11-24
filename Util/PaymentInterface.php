<?php
/**
 * Created by PhpStorm.
 * User: lpezzali
 * Date: 09/08/16
 * Time: 09:32
 */

namespace LpWeb\PaymentBundle\Util;


use Symfony\Component\DependencyInjection\Container;
use Symfony\Component\DependencyInjection\ContainerAware;
use Symfony\Component\HttpFoundation\Request;

abstract class PaymentInterface extends ContainerAware {

	function __construct(Container $container) {
		parent::setContainer($container);
	}

	/**
	 * @var string Currency constant
	 * @see PayPalCurrency
	 */
	private $currency = PaymentCurrency::CURRENCY_EUR;
	private $logLevel;
	private $mode;

	/**
	 * @return string
	 */
	public function getCurrency() {
		return $this->currency;
	}

	public abstract function configure();

	public abstract function overrideConfiguration(array $data);

	public abstract function notify(Request $request, $uniqueId);

	public abstract function success(Request $request, $uniqueId);

	public abstract function cancel($uniqueId);

}