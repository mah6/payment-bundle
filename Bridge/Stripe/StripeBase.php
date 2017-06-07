<?php

namespace PaymentBundle\Bridge\Stripe;

use Doctrine\ORM\EntityManager;
use Symfony\Component\Security\Core\Authentication\Token\Storage\TokenStorage;
use Symfony\Component\OptionsResolver\OptionsResolver;

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

	public function bind($stripeObject, $entity, array $mapping = array())
	{
		// $vars = get_object_vars($stripeObject);
		$vars = $stripeObject->__toArray(true);

		foreach ($vars as $property => $value) {

			if (array_key_exists($property, $mapping)) {
				$property = $mapping[$property];
			}

			self::callUserFunc($entity, $property, $value, 'set');
		}
	}

	public static function callUserFunc($object, $property, $params = null, $prefix = 'get')
	{
		$property = explode('_', $property);
		array_walk($property, function (&$val) {
			$val = ucfirst($val);
		});
		$property = implode($property);
		$method = $prefix . ucfirst($property);

		if (method_exists($object, $method)) {
			return call_user_func([$object, $method], $params);
		}

		return ;
	}

}