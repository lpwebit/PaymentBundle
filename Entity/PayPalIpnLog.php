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
	 * @ORM\Column(type="text")
	 */
	private $request;

	/**
	 * @return int
	 */
	public function getId() {
		return $this->id;
	}

	/**
	 * @return string
	 */
	public function getRequest() {
		return $this->request;
	}

	/**
	 * @param string $request
	 */
	public function setRequest($request) {
		$this->request = $request;

		return $this;
	}

}