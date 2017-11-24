<?php
/**
 * Created by PhpStorm.
 * User: lpezzali
 * Date: 08/08/16
 * Time: 17:20
 */

namespace LpWeb\PaymentBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * PayPalRequest
 *
 * @ORM\Table()
 * @ORM\Entity()
 */
class PaymentRequestItem {

	/**
	 * @var integer
	 *
	 * @ORM\Column(name="id", type="integer")
	 * @ORM\Id
	 * @ORM\GeneratedValue(strategy="AUTO")
	 */
	private $id;
	/**
	 * @var \LpWeb\PaymentBundle\Entity\PaymentRequestItem
	 *
	 * @ORM\ManyToOne(targetEntity="PaymentRequestItem", inversedBy="requestItems")
	 * @ORM\JoinColumn(name="request", referencedColumnName="id", nullable=false)
	 */
	private $request;
	/**
	 * @var string
	 *
	 * @ORM\Column(type="string", length=20)
	 */
	private $sku;
	/**
	 * @var double
	 *
	 * @ORM\Column(type="decimal")
	 */
	private $amount;
	/**
	 * @var string
	 *
	 * @ORM\Column(type="string", length=255)
	 */
	private $description;
	/**
	 * @var integer
	 *
	 * @ORM\Column(type="integer")
	 */
	private $quantity;

	/**
	 * @return int
	 */
	public function getId() {
		return $this->id;
	}

	/**
	 * @param int $id
	 * @return PaymentRequestItem
	 */
	public function setId($id) {
		$this->id = $id;
		return $this;
	}

	/**
	 * @return PaymentRequest
	 */
	public function getRequest() {
		return $this->request;
	}

	/**
	 * @param PaymentRequest $request
	 * @return PaymentRequestItem
	 */
	public function setRequest($request) {
		$this->request = $request;
		return $this;
	}

	/**
	 * @return float
	 */
	public function getAmount() {
		return $this->amount;
	}

	/**
	 * @param float $amount
	 * @return PaymentRequestItem
	 */
	public function setAmount($amount) {
		$this->amount = $amount;
		return $this;
	}

	/**
	 * @return string
	 */
	public function getSku() {
		return $this->sku;
	}

	/**
	 * @param string $sku
	 */
	public function setSku($sku) {
		$this->sku = $sku;
	}

	/**
	 * @return string
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
	 * @return int
	 */
	public function getQuantity() {
		return $this->quantity;
	}

	/**
	 * @param int $quantity
	 */
	public function setQuantity($quantity) {
		$this->quantity = $quantity;
	}

}