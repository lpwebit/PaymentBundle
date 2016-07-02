<?php

namespace LpWeb\PaymentBundle\Entity;


use Doctrine\ORM\Mapping as ORM;

/**
 * PayPalIpnLog
 *
 * @ORM\Table()
 * @ORM\Entity()
 * @ORM\HasLifecycleCallbacks
 */
class PayPalIpnLog {

	/**
	 * @var integer
	 *
	 * @ORM\Column(name="id", type="integer")
	 * @ORM\Id
	 * @ORM\GeneratedValue(strategy="AUTO")
	 */
	private $id;

	/**
	 * @var string
	 *
	 * @ORM\Column(type="string", length=255)
	 */
	private $uniqueId;
	/**
	 * @var string
	 *
	 * @ORM\Column(type="text", nullable=true)
	 */
	private $mc_gross;
	/**
	 * @var string
	 *
	 * @ORM\Column(type="text", nullable=true)
	 */
	private $invoice;
	/**
	 * @var string
	 *
	 * @ORM\Column(type="text", nullable=true)
	 */
	private $protection_eligibility;
	/**
	 * @var string
	 *
	 * @ORM\Column(type="text", nullable=true)
	 */
	private $item_number1;
	/**
	 * @var string
	 *
	 * @ORM\Column(type="text", nullable=true)
	 */
	private $payer_id;
	/**
	 * @var string
	 *
	 * @ORM\Column(type="text", nullable=true)
	 */
	private $tax;
	/**
	 * @var string
	 *
	 * @ORM\Column(type="text", nullable=true)
	 */
	private $payment_date;
	/**
	 * @var string
	 *
	 * @ORM\Column(type="text", nullable=true)
	 */
	private $payment_status;
	/**
	 * @var string
	 *
	 * @ORM\Column(type="text", nullable=true)
	 */
	private $charset;
	/**
	 * @var string
	 *
	 * @ORM\Column(type="text", nullable=true)
	 */
	private $mc_shipping;
	/**
	 * @var string
	 *
	 * @ORM\Column(type="text", nullable=true)
	 */
	private $mc_handling;
	/**
	 * @var string
	 *
	 * @ORM\Column(type="text", nullable=true)
	 */
	private $first_name;
	/**
	 * @var string
	 *
	 * @ORM\Column(type="text", nullable=true)
	 */
	private $mc_fee;
	/**
	 * @var string
	 *
	 * @ORM\Column(type="text", nullable=true)
	 */
	private $notify_version;
	/**
	 * @var string
	 *
	 * @ORM\Column(type="text", nullable=true)
	 */
	private $custom;
	/**
	 * @var string
	 *
	 * @ORM\Column(type="text", nullable=true)
	 */
	private $payer_status;
	/**
	 * @var string
	 *
	 * @ORM\Column(type="text", nullable=true)
	 */
	private $business;
	/**
	 * @var string
	 *
	 * @ORM\Column(type="text", nullable=true)
	 */
	private $num_cart_items;
	/**
	 * @var string
	 *
	 * @ORM\Column(type="text", nullable=true)
	 */
	private $mc_handling1;
	/**
	 * @var string
	 *
	 * @ORM\Column(type="text", nullable=true)
	 */
	private $payer_email;
	/**
	 * @var string
	 *
	 * @ORM\Column(type="text", nullable=true)
	 */
	private $verify_sign;
	/**
	 * @var string
	 *
	 * @ORM\Column(type="text", nullable=true)
	 */
	private $mc_shipping1;
	/**
	 * @var string
	 *
	 * @ORM\Column(type="text", nullable=true)
	 */
	private $tax1;
	/**
	 * @var string
	 *
	 * @ORM\Column(type="text", nullable=true)
	 */
	private $txn_id;
	/**
	 * @var string
	 *
	 * @ORM\Column(type="text", nullable=true)
	 */
	private $payment_type;
	/**
	 * @var string
	 *
	 * @ORM\Column(type="text", nullable=true)
	 */
	private $last_name;
	/**
	 * @var string
	 *
	 * @ORM\Column(type="text", nullable=true)
	 */
	private $item_name1;
	/**
	 * @var string
	 *
	 * @ORM\Column(type="text", nullable=true)
	 */
	private $receiver_email;
	/**
	 * @var string
	 *
	 * @ORM\Column(type="text", nullable=true)
	 */
	private $payment_fee;
	/**
	 * @var string
	 *
	 * @ORM\Column(type="text", nullable=true)
	 */
	private $quantity1;
	/**
	 * @var string
	 *
	 * @ORM\Column(type="text", nullable=true)
	 */
	private $receiver_id;
	/**
	 * @var string
	 *
	 * @ORM\Column(type="text", nullable=true)
	 */
	private $txn_type;
	/**
	 * @var string
	 *
	 * @ORM\Column(type="text", nullable=true)
	 */
	private $mc_gross_1;
	/**
	 * @var string
	 *
	 * @ORM\Column(type="text", nullable=true)
	 */
	private $mc_currency;
	/**
	 * @var string
	 *
	 * @ORM\Column(type="text", nullable=true)
	 */
	private $residence_country;
	/**
	 * @var string
	 *
	 * @ORM\Column(type="text", nullable=true)
	 */
	private $test_ipn;
	/**
	 * @var string
	 *
	 * @ORM\Column(type="text", nullable=true)
	 */
	private $transaction_subject;
	/**
	 * @var string
	 *
	 * @ORM\Column(type="text", nullable=true)
	 */
	private $payment_gross;
	/**
	 * @var string
	 *
	 * @ORM\Column(type="text", nullable=true)
	 */
	private $auth;

	/**
	 * @var \DateTime
	 *
	 * @ORM\Column(name="added", type="datetimetz")
	 */
	private $added;

	function __construct() {
		$this->added = new \DateTime();
	}

	/**
	 * @return int
	 */
	public function getId() {
		return $this->id;
	}

	/**
	 * @return string
	 */
	public function getMcFee() {
		return $this->mc_fee;
	}

	/**
	 * @param string $mc_fee
	 * @return PayPalIpnLog
	 */
	public function setMcFee($mc_fee) {
		$this->mc_fee = $mc_fee;
		return $this;
	}

	/**
	 * @return string
	 */
	public function getMcGross() {
		return $this->mc_gross;
	}

	/**
	 * @param string $mc_gross
	 * @return PayPalIpnLog
	 */
	public function setMcGross($mc_gross) {
		$this->mc_gross = $mc_gross;
		return $this;
	}

	/**
	 * @return string
	 */
	public function getInvoice() {
		return $this->invoice;
	}

	/**
	 * @param string $invoice
	 * @return PayPalIpnLog
	 */
	public function setInvoice($invoice) {
		$this->invoice = $invoice;
		return $this;
	}

	/**
	 * @return string
	 */
	public function getProtectionEligibility() {
		return $this->protection_eligibility;
	}

	/**
	 * @param string $protection_eligibility
	 * @return PayPalIpnLog
	 */
	public function setProtectionEligibility($protection_eligibility) {
		$this->protection_eligibility = $protection_eligibility;
		return $this;
	}

	/**
	 * @return string
	 */
	public function getItemNumber1() {
		return $this->item_number1;
	}

	/**
	 * @param string $item_number1
	 * @return PayPalIpnLog
	 */
	public function setItemNumber1($item_number1) {
		$this->item_number1 = $item_number1;
		return $this;
	}

	/**
	 * @return string
	 */
	public function getPayerId() {
		return $this->payer_id;
	}

	/**
	 * @param string $payer_id
	 * @return PayPalIpnLog
	 */
	public function setPayerId($payer_id) {
		$this->payer_id = $payer_id;
		return $this;
	}

	/**
	 * @return string
	 */
	public function getTax() {
		return $this->tax;
	}

	/**
	 * @param string $tax
	 * @return PayPalIpnLog
	 */
	public function setTax($tax) {
		$this->tax = $tax;
		return $this;
	}

	/**
	 * @return string
	 */
	public function getPaymentDate() {
		return $this->payment_date;
	}

	/**
	 * @param string $payment_date
	 * @return PayPalIpnLog
	 */
	public function setPaymentDate($payment_date) {
		$this->payment_date = $payment_date;
		return $this;
	}

	/**
	 * @return string
	 */
	public function getPaymentStatus() {
		return $this->payment_status;
	}

	/**
	 * @param string $payment_status
	 * @return PayPalIpnLog
	 */
	public function setPaymentStatus($payment_status) {
		$this->payment_status = $payment_status;
		return $this;
	}

	/**
	 * @return string
	 */
	public function getCharset() {
		return $this->charset;
	}

	/**
	 * @param string $charset
	 * @return PayPalIpnLog
	 */
	public function setCharset($charset) {
		$this->charset = $charset;
		return $this;
	}

	/**
	 * @return string
	 */
	public function getMcShipping() {
		return $this->mc_shipping;
	}

	/**
	 * @param string $mc_shipping
	 * @return PayPalIpnLog
	 */
	public function setMcShipping($mc_shipping) {
		$this->mc_shipping = $mc_shipping;
		return $this;
	}

	/**
	 * @return string
	 */
	public function getMcHandling() {
		return $this->mc_handling;
	}

	/**
	 * @param string $mc_handling
	 * @return PayPalIpnLog
	 */
	public function setMcHandling($mc_handling) {
		$this->mc_handling = $mc_handling;
		return $this;
	}

	/**
	 * @return string
	 */
	public function getFirstName() {
		return $this->first_name;
	}

	/**
	 * @param string $first_name
	 * @return PayPalIpnLog
	 */
	public function setFirstName($first_name) {
		$this->first_name = $first_name;
		return $this;
	}

	/**
	 * @return string
	 */
	public function getNotifyVersion() {
		return $this->notify_version;
	}

	/**
	 * @param string $notify_version
	 * @return PayPalIpnLog
	 */
	public function setNotifyVersion($notify_version) {
		$this->notify_version = $notify_version;
		return $this;
	}

	/**
	 * @return string
	 */
	public function getCustom() {
		return $this->custom;
	}

	/**
	 * @param string $custom
	 * @return PayPalIpnLog
	 */
	public function setCustom($custom) {
		$this->custom = $custom;
		return $this;
	}

	/**
	 * @return string
	 */
	public function getPayerStatus() {
		return $this->payer_status;
	}

	/**
	 * @param string $payer_status
	 * @return PayPalIpnLog
	 */
	public function setPayerStatus($payer_status) {
		$this->payer_status = $payer_status;
		return $this;
	}

	/**
	 * @return string
	 */
	public function getBusiness() {
		return $this->business;
	}

	/**
	 * @param string $business
	 * @return PayPalIpnLog
	 */
	public function setBusiness($business) {
		$this->business = $business;
		return $this;
	}

	/**
	 * @return string
	 */
	public function getNumCartItems() {
		return $this->num_cart_items;
	}

	/**
	 * @param string $num_cart_items
	 * @return PayPalIpnLog
	 */
	public function setNumCartItems($num_cart_items) {
		$this->num_cart_items = $num_cart_items;
		return $this;
	}

	/**
	 * @return string
	 */
	public function getMcHandling1() {
		return $this->mc_handling1;
	}

	/**
	 * @param string $mc_handling1
	 * @return PayPalIpnLog
	 */
	public function setMcHandling1($mc_handling1) {
		$this->mc_handling1 = $mc_handling1;
		return $this;
	}

	/**
	 * @return string
	 */
	public function getPayerEmail() {
		return $this->payer_email;
	}

	/**
	 * @param string $payer_email
	 * @return PayPalIpnLog
	 */
	public function setPayerEmail($payer_email) {
		$this->payer_email = $payer_email;
		return $this;
	}

	/**
	 * @return string
	 */
	public function getVerifySign() {
		return $this->verify_sign;
	}

	/**
	 * @param string $verify_sign
	 * @return PayPalIpnLog
	 */
	public function setVerifySign($verify_sign) {
		$this->verify_sign = $verify_sign;
		return $this;
	}

	/**
	 * @return string
	 */
	public function getMcShipping1() {
		return $this->mc_shipping1;
	}

	/**
	 * @param string $mc_shipping1
	 * @return PayPalIpnLog
	 */
	public function setMcShipping1($mc_shipping1) {
		$this->mc_shipping1 = $mc_shipping1;
		return $this;
	}

	/**
	 * @return string
	 */
	public function getTax1() {
		return $this->tax1;
	}

	/**
	 * @param string $tax1
	 * @return PayPalIpnLog
	 */
	public function setTax1($tax1) {
		$this->tax1 = $tax1;
		return $this;
	}

	/**
	 * @return string
	 */
	public function getTxnId() {
		return $this->txn_id;
	}

	/**
	 * @param string $txn_id
	 * @return PayPalIpnLog
	 */
	public function setTxnId($txn_id) {
		$this->txn_id = $txn_id;
		return $this;
	}

	/**
	 * @return string
	 */
	public function getPaymentType() {
		return $this->payment_type;
	}

	/**
	 * @param string $payment_type
	 * @return PayPalIpnLog
	 */
	public function setPaymentType($payment_type) {
		$this->payment_type = $payment_type;
		return $this;
	}

	/**
	 * @return string
	 */
	public function getLastName() {
		return $this->last_name;
	}

	/**
	 * @param string $last_name
	 * @return PayPalIpnLog
	 */
	public function setLastName($last_name) {
		$this->last_name = $last_name;
		return $this;
	}

	/**
	 * @return string
	 */
	public function getItemName1() {
		return $this->item_name1;
	}

	/**
	 * @param string $item_name1
	 * @return PayPalIpnLog
	 */
	public function setItemName1($item_name1) {
		$this->item_name1 = $item_name1;
		return $this;
	}

	/**
	 * @return string
	 */
	public function getReceiverEmail() {
		return $this->receiver_email;
	}

	/**
	 * @param string $receiver_email
	 * @return PayPalIpnLog
	 */
	public function setReceiverEmail($receiver_email) {
		$this->receiver_email = $receiver_email;
		return $this;
	}

	/**
	 * @return string
	 */
	public function getPaymentFee() {
		return $this->payment_fee;
	}

	/**
	 * @param string $payment_fee
	 * @return PayPalIpnLog
	 */
	public function setPaymentFee($payment_fee) {
		$this->payment_fee = $payment_fee;
		return $this;
	}

	/**
	 * @return string
	 */
	public function getQuantity1() {
		return $this->quantity1;
	}

	/**
	 * @param string $quantity1
	 * @return PayPalIpnLog
	 */
	public function setQuantity1($quantity1) {
		$this->quantity1 = $quantity1;
		return $this;
	}

	/**
	 * @return string
	 */
	public function getReceiverId() {
		return $this->receiver_id;
	}

	/**
	 * @param string $receiver_id
	 * @return PayPalIpnLog
	 */
	public function setReceiverId($receiver_id) {
		$this->receiver_id = $receiver_id;
		return $this;
	}

	/**
	 * @return string
	 */
	public function getTxnType() {
		return $this->txn_type;
	}

	/**
	 * @param string $txn_type
	 * @return PayPalIpnLog
	 */
	public function setTxnType($txn_type) {
		$this->txn_type = $txn_type;
		return $this;
	}

	/**
	 * @return string
	 */
	public function getMcGross1() {
		return $this->mc_gross_1;
	}

	/**
	 * @param string $mc_gross_1
	 * @return PayPalIpnLog
	 */
	public function setMcGross1($mc_gross_1) {
		$this->mc_gross_1 = $mc_gross_1;
		return $this;
	}

	/**
	 * @return string
	 */
	public function getMcCurrency() {
		return $this->mc_currency;
	}

	/**
	 * @param string $mc_currency
	 * @return PayPalIpnLog
	 */
	public function setMcCurrency($mc_currency) {
		$this->mc_currency = $mc_currency;
		return $this;
	}

	/**
	 * @return string
	 */
	public function getResidenceCountry() {
		return $this->residence_country;
	}

	/**
	 * @param string $residence_country
	 * @return PayPalIpnLog
	 */
	public function setResidenceCountry($residence_country) {
		$this->residence_country = $residence_country;
		return $this;
	}

	/**
	 * @return string
	 */
	public function getTestIpn() {
		return $this->test_ipn;
	}

	/**
	 * @param string $test_ipn
	 * @return PayPalIpnLog
	 */
	public function setTestIpn($test_ipn) {
		$this->test_ipn = $test_ipn;
		return $this;
	}

	/**
	 * @return string
	 */
	public function getTransactionSubject() {
		return $this->transaction_subject;
	}

	/**
	 * @param string $transaction_subject
	 * @return PayPalIpnLog
	 */
	public function setTransactionSubject($transaction_subject) {
		$this->transaction_subject = $transaction_subject;
		return $this;
	}

	/**
	 * @return string
	 */
	public function getPaymentGross() {
		return $this->payment_gross;
	}

	/**
	 * @param string $payment_gross
	 * @return PayPalIpnLog
	 */
	public function setPaymentGross($payment_gross) {
		$this->payment_gross = $payment_gross;
		return $this;
	}

	/**
	 * @return string
	 */
	public function getAuth() {
		return $this->auth;
	}

	/**
	 * @param string $auth
	 * @return PayPalIpnLog
	 */
	public function setAuth($auth) {
		$this->auth = $auth;
		return $this;
	}

	/**
	 * @return string
	 */
	public function getUniqueId() {
		return $this->uniqueId;
	}

	/**
	 * @param string $uniqueId
	 */
	public function setUniqueId($uniqueId) {
		$this->uniqueId = $uniqueId;
	}

	/**
	 * @return \DateTime
	 */
	public function getAdded() {
		return $this->added;
	}

	/**
	 * @param \DateTime $added
	 * @return PayPalIpnLog
	 */
	public function setAdded($added) {
		$this->added = $added;
		return $this;
	}

}
