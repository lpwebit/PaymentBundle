<?php
/**
 * Created by PhpStorm.
 * User: lpezzali
 * Date: 29/12/15
 * Time: 16:19
 */

namespace LpWeb\PaymentBundle\Entity;

use Doctrine\ORM\Mapping as ORM;


/**
 * PayPalRequest
 *
 * @ORM\Table(uniqueConstraints={@ORM\UniqueConstraint(name="uniqueid_idx", columns={"unique_id"})})
 * @ORM\Entity()
 * @ORM\HasLifecycleCallbacks
 */
class PayPalRequest {

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
	 * @ORM\Column(type="text")
	 */
	private $cmd;
	/**
	 * @var string
	 *
	 * @ORM\Column(type="text")
	 */
	private $upload;
	/**
	 * @var string
	 *
	 * @ORM\Column(type="text")
	 */
	private $business;
	/**
	 * @var string
	 *
	 * @ORM\Column(type="text")
	 */
	private $bn;
	/**
	 * @var string
	 *
	 * @ORM\Column(type="text")
	 */
	private $charset;
	/**
	 * @var string
	 *
	 * @ORM\Column(type="text")
	 */
	private $no_note;
	/**
	 * @var string
	 *
	 * @ORM\Column(type="text")
	 */
	private $no_shipping;
	/**
	 * @var string
	 *
	 * @ORM\Column(type="text")
	 */
	private $cancel_return;
	/**
	 * @var string
	 *
	 * @ORM\Column(type="text")
	 */
	private $return;
	/**
	 * @var string
	 *
	 * @ORM\Column(type="text")
	 */
	private $notify_url;
	/**
	 * @var string
	 *
	 * @ORM\Column(type="text")
	 */
	private $rm;
	/**
	 * @var string
	 *
	 * @ORM\Column(type="text")
	 */
	private $paymentaction;
	/**
	 * @var string
	 *
	 * @ORM\Column(type="text")
	 */
	private $currency_code;
	/**
	 * @var string
	 *
	 * @ORM\Column(type="text")
	 */
	private $lc;
	/**
	 * @var string
	 *
	 * @ORM\Column(type="text")
	 */
	private $invoice;
	/**
	 * @var string
	 *
	 * @ORM\Column(type="text")
	 */
	private $amount_1;
	/**
	 * @var string
	 *
	 * @ORM\Column(type="text")
	 */
	private $item_name_1;
	/**
	 * @var string
	 *
	 * @ORM\Column(type="text", nullable=true)
	 */
	private $customData;

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
	public function getUniqueId() {
		return $this->uniqueId;
	}

	/**
	 * @param string $uniqueId
	 * @return PayPalRequest
	 */
	public function setUniqueId($uniqueId) {
		$this->uniqueId = $uniqueId;
		return $this;
	}

	/**
	 * @return string
	 */
	public function getCmd() {
		return $this->cmd;
	}

	/**
	 * @param string $cmd
	 * @return PayPalRequest
	 */
	public function setCmd($cmd) {
		$this->cmd = $cmd;
		return $this;
	}

	/**
	 * @return string
	 */
	public function getUpload() {
		return $this->upload;
	}

	/**
	 * @param string $upload
	 * @return PayPalRequest
	 */
	public function setUpload($upload) {
		$this->upload = $upload;
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
	 * @return PayPalRequest
	 */
	public function setBusiness($business) {
		$this->business = $business;
		return $this;
	}

	/**
	 * @return string
	 */
	public function getBn() {
		return $this->bn;
	}

	/**
	 * @param string $bn
	 * @return PayPalRequest
	 */
	public function setBn($bn) {
		$this->bn = $bn;
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
	 * @return PayPalRequest
	 */
	public function setCharset($charset) {
		$this->charset = $charset;
		return $this;
	}

	/**
	 * @return string
	 */
	public function getNoNote() {
		return $this->no_note;
	}

	/**
	 * @param string $no_note
	 * @return PayPalRequest
	 */
	public function setNoNote($no_note) {
		$this->no_note = $no_note;
		return $this;
	}

	/**
	 * @return string
	 */
	public function getNoShipping() {
		return $this->no_shipping;
	}

	/**
	 * @param string $no_shipping
	 * @return PayPalRequest
	 */
	public function setNoShipping($no_shipping) {
		$this->no_shipping = $no_shipping;
		return $this;
	}

	/**
	 * @return string
	 */
	public function getCancelReturn() {
		return $this->cancel_return;
	}

	/**
	 * @param string $cancel_return
	 * @return PayPalRequest
	 */
	public function setCancelReturn($cancel_return) {
		$this->cancel_return = $cancel_return;
		return $this;
	}

	/**
	 * @return string
	 */
	public function getReturn() {
		return $this->return;
	}

	/**
	 * @param string $return
	 * @return PayPalRequest
	 */
	public function setReturn($return) {
		$this->return = $return;
		return $this;
	}

	/**
	 * @return string
	 */
	public function getNotifyUrl() {
		return $this->notify_url;
	}

	/**
	 * @param string $notify_url
	 * @return PayPalRequest
	 */
	public function setNotifyUrl($notify_url) {
		$this->notify_url = $notify_url;
		return $this;
	}

	/**
	 * @return string
	 */
	public function getRm() {
		return $this->rm;
	}

	/**
	 * @param string $rm
	 * @return PayPalRequest
	 */
	public function setRm($rm) {
		$this->rm = $rm;
		return $this;
	}

	/**
	 * @return string
	 */
	public function getPaymentaction() {
		return $this->paymentaction;
	}

	/**
	 * @param string $paymentaction
	 * @return PayPalRequest
	 */
	public function setPaymentaction($paymentaction) {
		$this->paymentaction = $paymentaction;
		return $this;
	}

	/**
	 * @return string
	 */
	public function getCurrencyCode() {
		return $this->currency_code;
	}

	/**
	 * @param string $currency_code
	 * @return PayPalRequest
	 */
	public function setCurrencyCode($currency_code) {
		$this->currency_code = $currency_code;
		return $this;
	}

	/**
	 * @return string
	 */
	public function getLc() {
		return $this->lc;
	}

	/**
	 * @param string $lc
	 * @return PayPalRequest
	 */
	public function setLc($lc) {
		$this->lc = $lc;
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
	 * @return PayPalRequest
	 */
	public function setInvoice($invoice) {
		$this->invoice = $invoice;
		return $this;
	}

	/**
	 * @return string
	 */
	public function getAmount1() {
		return $this->amount_1;
	}

	/**
	 * @param string $amount_1
	 * @return PayPalRequest
	 */
	public function setAmount1($amount_1) {
		$this->amount_1 = $amount_1;
		return $this;
	}

	/**
	 * @return string
	 */
	public function getItemName1() {
		return $this->item_name_1;
	}

	/**
	 * @param string $item_name_1
	 * @return PayPalRequest
	 */
	public function setItemName1($item_name_1) {
		$this->item_name_1 = $item_name_1;
		return $this;
	}

	/**
	 * @return string
	 */
	public function getCustomData() {
		return $this->customData;
	}

	/**
	 * @param string $customData
	 * @return PayPalRequest
	 */
	public function setCustomData($customData) {
		$this->customData = $customData;
		return $this;
	}

	/**
	 * @return \DateTime
	 */
	public function getAdded() {
		return $this->added;
	}

	/**
	 * @param \DateTime $added
	 * @return PayPalRequest
	 */
	public function setAdded($added) {
		$this->added = $added;
		return $this;
	}

}
