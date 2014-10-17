<?php

namespace SS6\ShopBundle\Form\Admin\Order\Status;

use SS6\ShopBundle\Form\Admin\Mail\MailTemplateFormType;
use SS6\ShopBundle\Model\Order\Status\OrderStatusMailTemplatesData;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolverInterface;

class OrderStatusMailTemplatesFormType extends AbstractType {

	/**
	 * @return string
	 */
	public function getName() {
		return 'order_status_mail_templates';
	}

	/**
	 * @param \Symfony\Component\Form\FormBuilderInterface $builder
	 * @param array $options
	 */
	public function buildForm(FormBuilderInterface $builder, array $options) {
		$builder
			->add('templates', 'collection', array(
				'type' => new MailTemplateFormType(),
			));
	}

	/**
	 * @param \Symfony\Component\OptionsResolver\OptionsResolverInterface $resolver
	 */
	public function setDefaultOptions(OptionsResolverInterface $resolver) {
		$resolver->setDefaults(array(
			'attr' => array('novalidate' => 'novalidate'),
			'data_class' => OrderStatusMailTemplatesData::class,
		));
	}

}