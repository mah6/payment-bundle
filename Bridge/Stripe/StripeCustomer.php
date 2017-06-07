<?php

namespace PaymentBundle\Bridge\Stripe;

class StripeCustomer extends StripeBase
{
	const SERVICE_NAME = "payment.stripe.customer";

	/**
	 * Create a \Stripe\Customer
	 * Required $options key:
	 * 	- email
	 *
	 * @param array $options
	 * @return \Stripe\Customer 
	 */
	public function createCustomer(array $options)
	{
		$options = $this->configureOptions($options);
		$customer = Customer::create($options);

		return $customer;
	}

	public function configureOptions(array $options)
	{
		$resolver = new OptionsResolver();
		$resolver->setRequired('email');
		$resolver->setDefined('source');
		$resolver->setDefined('metadata');

		return $resolver->resolve($options);
	}

}