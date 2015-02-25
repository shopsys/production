<?php

namespace SS6\ShopBundle\Component\DataFixture;

use Doctrine\Common\DataFixtures\AbstractFixture;
use Doctrine\ORM\Query\ResultSetMapping;
use Symfony\Component\DependencyInjection\ContainerAwareInterface;
use Symfony\Component\DependencyInjection\ContainerInterface;

abstract class AbstractNativeFixture extends AbstractFixture implements ContainerAwareInterface {

	/**
	 * @var \Symfony\Component\DependencyInjection\ContainerInterface
	 */
	private $container;

	/**
	 * @param \Doctrine\ORM\EntityManager $em
	 * @param \Symfony\Component\DependencyInjection\ContainerInterface $container
	 */
	public function setContainer(ContainerInterface $container = null) {
		$this->container = $container;
	}

	/**
	 * @param string $sql
	 * @param arra
	 * @return mixed
	 */
	protected function executeNativeQuery($sql, array $parameters = null) {
		$em = $this->container->get('doctrine.orm.entity_manager');
		/* @var $em \Doctrine\ORM\EntityManager */

		$nativeQuery = $em->createNativeQuery($sql, new ResultSetMapping());
		return $nativeQuery->execute($parameters);
	}

}