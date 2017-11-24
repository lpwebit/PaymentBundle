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
class PaymentRequest {

	/**
	 * @var integer
	 *
	 * @ORM\Column(name="id", type="integer")
	 * @ORM\Id
	 * @ORM\GeneratedValue(strategy="AUTO")
	 */
	private $id;
	/**
	 * @var integer
	 *
	 * @ORM\Column(type="decimal")
	 */
	private $uniqueId;
	/**
	 * @var string
	 *
	 * @ORM\Column(type="string", length=30)
	 */
	private $paymentMethod;
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
	private $return_url;
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
	 * @ORM\Column(type="text", nullable=true)
	 */
	private $customData;

	/**
	 * @var \DateTime
	 *
	 * @ORM\Column(name="added", type="datetimetz")
	 */
	private $added;
	/**
	 * @ORM\OneToMany(targetEntity="LpWeb\PaymentBundle\Entity\PaymentRequestItem", mappedBy="request")
	 */
	private $requestItems;


	function __construct() {
		$this->added = new \DateTime();
	}

	/**
	 * @return string
	 */
	public function getRm() {
		return $this->rm;
	}

	/**
	 * @param string $rm
	 * @return PaymentRequest
	 */
	public function setRm($rm) {
		$this->rm = $rm;
		return $this;
	}

	/**
	 * @return int
	 */
	public function getId() {
		return $this->id;
	}

	/**
	 * @param int $id
	 * @return PaymentRequest
	 */
	public function setId($id) {
		$this->id = $id;
		return $this;
	}

	/**
	 * @return int
	 */
	public function getUniqueId() {
		return $this->uniqueId;
	}

	/**
	 * @param int $uniqueId
	 * @return PaymentRequest
	 */
	public function setUniqueId($uniqueId) {
		$this->uniqueId = $uniqueId;
		return $this;
	}

	/**
	 * @return string
	 */
	public function getPaymentMethod() {
		return $this->paymentMethod;
	}

	/**
	 * @param string $paymentMethod
	 * @return PaymentRequest
	 */
	public function setPaymentMethod($paymentMethod) {
		$this->paymentMethod = $paymentMethod;
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
	 * @return PaymentRequest
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
	 * @return PaymentRequest
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
	 * @return PaymentRequest
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
	 * @return PaymentRequest
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
	 * @return PaymentRequest
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
	 * @return PaymentRequest
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
	 * @return PaymentRequest
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
	 * @return PaymentRequest
	 */
	public function setCancelReturn($cancel_return) {
		$this->cancel_return = $cancel_return;
		return $this;
	}

	/**
	 * @return string
	 */
	public function getReturnUrl() {
		return $this->return_url;
	}

	/**
	 * @param string $return_url
	 * @return PaymentRequest
	 */
	public function setReturnUrl($return_url) {
		$this->return_url = $return_url;
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
	 * @return PaymentRequest
	 */
	public function setNotifyUrl($notify_url) {
		$this->notify_url = $notify_url;
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
	 * @return PaymentRequest
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
	 * @return PaymentRequest
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
	 * @return PaymentRequest
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
	 * @return PaymentRequest
	 */
	public function setInvoice($invoice) {
		$this->invoice = $invoice;
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
	 * @return PaymentRequest
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
	 * @return PaymentRequest
	 */
	public function setAdded($added) {
		$this->added = $added;
		return $this;
	}

	/**
	 * @return mixed
	 */
	public function getRequestItems() {
		return $this->requestItems;
	}

	/**
	 * @param mixed $requestItems
	 * @return PaymentRequest
	 */
	public function setRequestItems($requestItems) {
		$this->requestItems = $requestItems;
		return $this;
	}

}
