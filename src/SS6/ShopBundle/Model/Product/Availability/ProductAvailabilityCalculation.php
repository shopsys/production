<?php

namespace SS6\ShopBundle\Model\Product\Availability;

use Doctrine\ORM\EntityManager;
use SS6\ShopBundle\Model\Product\Availability\AvailabilityFacade;
use SS6\ShopBundle\Model\Product\Product;
use SS6\ShopBundle\Model\Product\ProductSellingDeniedRecalculator;
use SS6\ShopBundle\Model\Product\ProductVisibilityFacade;

class ProductAvailabilityCalculation {

	/**
	 * @var \SS6\ShopBundle\Model\Product\Availability\AvailabilityFacade
	 */
	private $availabilityFacade;

	/**
	 * @param \SS6\ShopBundle\Model\Product\ProductSellingDeniedRecalculator $productSellingDeniedRecalculator
	 */
	private $productSellingDeniedRecalculator;

	/**
	 * @var \SS6\ShopBundle\Model\Product\ProductVisibilityFacade
	 */
	private $productVisibilityFacade;

	/**
	 * @var \Doctrine\ORM\EntityManager
	 */
	private $em;

	public function __construct(
		AvailabilityFacade $availabilityFacade,
		ProductSellingDeniedRecalculator $productSellingDeniedRecalculator,
		ProductVisibilityFacade $productVisibilityFacade,
		EntityManager $em
	) {
		$this->availabilityFacade = $availabilityFacade;
		$this->productSellingDeniedRecalculator = $productSellingDeniedRecalculator;
		$this->em = $em;
		$this->productVisibilityFacade = $productVisibilityFacade;
	}

	/**
	 * @param \SS6\ShopBundle\Model\Product\Product $product
	 * @return \SS6\ShopBundle\Model\Product\Availability\Availability
	 */
	public function getCalculatedAvailability(Product $product) {
		if ($product->isMainVariant()) {
			return $this->getMainVariantCalculatedAvailability($product);
		}
		if ($product->isUsingStock()) {
			if ($product->getStockQuantity() <= 0
				&& $product->getOutOfStockAction() === Product::OUT_OF_STOCK_ACTION_SET_ALTERNATE_AVAILABILITY
			) {
				return $product->getOutOfStockAvailability();
			} else {
				return $this->availabilityFacade->getDefaultInStockAvailability();
			}
		} else {
			return $product->getAvailability();
		}
	}

	/**
	 * @param \SS6\ShopBundle\Model\Product\Product $mainVariant
	 * @return \SS6\ShopBundle\Model\Product\Availability\Availability
	 */
	private function getMainVariantCalculatedAvailability(Product $mainVariant) {
		$atLeastSomewhereSellableVariants = $this->getAtLeastSomewhereSellableVariantsByMainVariant($mainVariant);
		if (count($atLeastSomewhereSellableVariants) === 0) {
			return $this->availabilityFacade->getDefaultInStockAvailability();
		}
		$fastestAvailability = $this->getCalculatedAvailability(array_shift($atLeastSomewhereSellableVariants));

		foreach ($atLeastSomewhereSellableVariants as $variant) {
			$variantCalculatedAvailability = $this->getCalculatedAvailability($variant);
			if ($fastestAvailability->getDispatchTime() === null
				|| $variantCalculatedAvailability->getDispatchTime() !== null
				&& $variantCalculatedAvailability->getDispatchTime() < $fastestAvailability->getDispatchTime()
			) {
				$fastestAvailability = $variantCalculatedAvailability;
			}
		}

		return $fastestAvailability;
	}

	/**
	 * @param \SS6\ShopBundle\Model\Product\Product $mainVariant
	 * @return \SS6\ShopBundle\Model\Product\Product[]
	 */
	private function getAtLeastSomewhereSellableVariantsByMainVariant(Product $mainVariant) {
		$allVariants = $mainVariant->getVariants();
		foreach ($allVariants as $variant) {
			$this->productSellingDeniedRecalculator->calculateSellingDeniedForProduct($variant);
			$variant->markForVisibilityRecalculation();
		}
		$this->em->flush();
		$this->productVisibilityFacade->refreshProductsVisibilityForMarked();

		$atLeastSomewhereSellableVariants = [];
		foreach ($allVariants as $variant) {
			$this->em->refresh($variant);
			if ($variant->getCalculatedSellingDenied() === false && $variant->isVisible()) {
				$atLeastSomewhereSellableVariants[] = $variant;
			}
		}

		return $atLeastSomewhereSellableVariants;
	}

}
