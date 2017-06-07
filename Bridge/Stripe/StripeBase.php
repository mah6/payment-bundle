<?php

namespace PaymentBundle\Bridge\Stripe;

use Stripe\Stripe;
use Stripe\Charge;
use Stripe\Customer;
use Stripe\Plan;
use Stripe\Subscription;

class StripeBase
{
	protected $apiKeys;
	protected $em;
	protected $tokenStorage;

	public function __construct($apiKeys, EntityManager $em, TokenStorage $tokenStorage)
	{
		$this->apiKeys = $apiKeys;
		$this->em = $em;
		$this->tokenStorage = $tokenStorage;
		Stripe::setApiKey($this->apiKeys['secret_key']);
	}

	public static function bind($stripeObject, $entity, array $options = null)
	{
		$vars = get_object_vars($stripeObject);

		foreach ($vars as $property => $value) {

			$this->callUserFunc($entity, $property, $value, 'set');

		}
	}

	public static function callUserFunc($object, $property, $params, $prefix = 'get')
	{
		$method = $prefix . ucfirst($property);

		if (method_exists($object, $method)) {
			return call_user_func([$object => $method], $params);
		}

		return ;
	}

}