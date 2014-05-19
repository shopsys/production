<?php

namespace SS6\ShopBundle\Model\Order\Exception;

use Exception;

class OrderNumberSequenceNotFoundException extends Exception implements OrderException {
	
	/**
	 * @param string $message
	 * @param Exception $previous
	 */
	public function __construct($message = null, $previous = null) {
		parent::__construct($message, 0, $previous);
	}
}