<?php
/**
 * Created by PhpStorm.
 * User: lpezzali
 * Date: 29/12/15
 * Time: 14:06
 */

namespace LpWeb\PaymentBundle;

/**
 * Contains all events thrown in the LpWebPaymentBundle
 */
final class LpWebPaymentEvents {

	/**
	 * The CHANGE_PASSWORD_INITIALIZE event occurs when the change password process is initialized.
	 *
	 * This event allows you to modify the default values of the user before binding the form.
	 * The event listener method receives a FOS\UserBundle\Event\GetResponseUserEvent instance.
	 */
	const NOTIFY_PAYPAL_PAYMENT_IPN = 'lpweb_payment.paypal.ipn_notify';

}