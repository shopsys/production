<?php

namespace SS6\ShopBundle\Model\Order\Item;

class OrderItemData {
	
	/**
	 * @var int
	 */
	private $id;

	/**
	 * @var string
	 */
	private $name;

	/**
	 * @var string
	 */
	private $price;

	/**
	 * @var int
	 */
	private $quantity;

	/**
	 *
	 * @return int
	 */
	public function getId() {
		return $this->id;
	}

	/**
	 * @return string
	 */
	public function getName() {
		return $this->name;
	}

	/**
	 * @return string
	 */
	public function getPrice() {
		return $this->price;
	}

	/**
	 * @return int
	 */
	public function getQuantity() {
		return $this->quantity;
	}

	/**
	 * @param int $id
	 */
	public function setId($id) {
		$this->id = $id;
	}

	/**
	 * @param string $name
	 */
	public function setName($name) {
		$this->name = $name;
	}

	/**
	 * @param string $price
	 */
	public function setPrice($price) {
		$this->price = $price;
	}

	/**
	 * @param string $quantity
	 */
	public function setQuantity($quantity) {
		$this->quantity = $quantity;
	}

	/**
	 * @param \SS6\ShopBundle\Model\Order\Item\OrderItem $orderItem
	 */
	public function setFromEntity(OrderItem $orderItem) {
		$this->setId($orderItem->getId());
		$this->setName($orderItem->getName());
		$this->setPrice($orderItem->getPriceWithVat());
		$this->setQuantity($orderItem->getQuantity());
	}

}