<?php

namespace PaymentBundle\Bridge\Stripe;

use Symfony\Component\OptionsResolver;

use Stripe\Stripe;
use Stripe\Charge;
use Stripe\Customer;
use Stripe\Plan;
use Stripe\Subscription;

class StripeSubscription
{
	public function __construct($apiKeys, EntityManager $em, TokenStorage $tokenStorage)
	{
		parent::__construct($apiKeys, $em, $tokenStorage);		
	}

	public function createSubscription($customerId, $planId)
	{
		$subscription = Subscription::create([
			'customer' => $customerId,
			'plan' => $planId,
		]);

		return $subscription;
	}

	public function configureOptions(array $options)
	{
		$resolver = new OptionsResolver();

		return $resolver->resolve($options);
	}
}