<?php

namespace PaymentBundle\Bridge\Stripe;

class StripeCustomer extends StripeBase
{
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
		$options = $this->configureCustomerOptions($options);
		$customer = Customer::create($options);
		
		return $customer;
	}

}