<?php

namespace SS6\ShopBundle\Model\Order\Status;

use SS6\ShopBundle\Model\Mail\AllMailTemplatesData;
use SS6\ShopBundle\Model\Mail\MailTemplateData;
use SS6\ShopBundle\Model\Order\Mail\OrderMailService;

class OrderStatusMailTemplateService {

	/**
	 * @var \SS6\ShopBundle\Model\Order\Mail\OrderMailService
	 */
	private $orderMailService;

	/**
	 * @param \SS6\ShopBundle\Model\Order\Mail\OrderMailService $orderMailService
	 */
	public function __construct(OrderMailService $orderMailService) {
		$this->orderMailService = $orderMailService;
	}

	/**
	 * @param \SS6\ShopBundle\Model\Mail\MailTemplate[] $mailTemplates
	 * @param \SS6\ShopBundle\Model\Order\Status\OrderStatus $orderStatus
	 * @return \SS6\ShopBundle\Model\Mail\MailTemplate|null
	 */
	private function getMailTemplateByOrderStatus(array $mailTemplates, OrderStatus $orderStatus) {
		foreach ($mailTemplates as $mailTemplate) {
			if ($mailTemplate->getName() === $this->orderMailService->getMailTemplateNameByStatus($orderStatus)) {
				return $mailTemplate;
			}
		}

		return null;
	}

	/**
	 * @param \SS6\ShopBundle\Model\Order\Status\OrderStatus[] $orderStatuses
	 * @param \SS6\ShopBundle\Model\Mail\MailTemplate[] $mailTemplates
	 * @return \SS6\ShopBundle\Model\Mail\MailTemplateData[]
	 */
	public function getOrderStatusMailTemplatesData(array $orderStatuses, array $mailTemplates) {
		$orderStatusMailTemplatesData = array();
		foreach ($orderStatuses as $orderStatus) {
			$orderStatusMailTemplateData = new MailTemplateData();

			$mailTemplate = $this->getMailTemplateByOrderStatus($mailTemplates, $orderStatus);
			if ($mailTemplate !== null) {
				$orderStatusMailTemplateData->setFromEntity($mailTemplate);
			} else {
				$orderStatusMailTemplateData->setSendMail(false);
			}
			$orderStatusMailTemplateData->setName($this->orderMailService->getMailTemplateNameByStatus($orderStatus));

			$orderStatusMailTemplatesData[$orderStatus->getId()] = $orderStatusMailTemplateData;
		}

		return $orderStatusMailTemplatesData;
	}

}
