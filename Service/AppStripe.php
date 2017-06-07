<?php

namespace PaymentBundle\Service;

use Symfony\Component\OptionsResolver\OptionsResolver;
use Doctrine\ORM\EntityManager;
use Symfony\Component\Security\Core\Authentication\Token\Storage\TokenStorage;

use Stripe\Stripe;
use Stripe\Charge;
use Stripe\Customer;
use Stripe\Plan;
use Stripe\Subscription; 

use AppBundle\Entity\User;
use PaymentBundle\Entity\StripePlan;

class AppStripe
{
	const SERVICE_NAME = 'app.stripe';
	private $apiKeys;
	private $em;
	private $tokenStorage;

	public function __construct($apiKeys, EntityManager $em, TokenStorage $tokenStorage)
	{
		$this->apiKeys = $apiKeys;
		$this->em = $em;
		$this->tokenStorage = $tokenStorage;
		Stripe::setApiKey($this->apiKeys['secret_key']);
	}

	/**
	 * Process a stripe payment without saving it
	 *
	 * Required $options keys:
	 * 	- token : a stripe token from Checkout or Elements
	 *	- amount : the payment amount
	 *
	 * @param array $options The payment options
	 * @return \Stripe\Charge
	 */
	public function pay(array $options)
	{
		$options = $this->configurePaymentOptions($options);

		$charge = Charge::create(array(
			"amount" => $options['amount'],
			"currency" => $options['currency'],
			"description" => $options['description'],
			"source" => $options['token'],
		));

		return $charge;
	}

	/**
	 * Save customer card information
	 * 
	 * Required $options keys:
	 * 	- amount
	 * 	- token
	 *
	 * @param $options
	 * @return \Stripe\Charge
	 */
	public function saveCustomerCard(array $options)
	{
		$customer = $this->createCustomer([
			'email' => $user->getEmail(),
			'source' => $options['token'],
		]);

		$charge = Charge::create(array(
			"amount" => $options['amount'],
			"currency" => $options['currency'],
			"customer" => $customer->id
		));

		return $charge;
	}

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

		// $appCustomer = new AppCustomer();
		// $appCustomer
		// 	->setCustomer($customer->id)
		// 	->setUser($this->getUser())
		// ;
		// $user->setStripeToken($customer->id);
		$this->em->persist($appCustomer);
		$this->em->flush();

		return $customer;
	}

	/**
	 * Subscribe Customer to a given Plan
	 *
	 * Create a \Stripe\Subscription for $customer for $plan
	 *
	 * @var string $customer The customer to subscribe to $plan
	 * @var string $plan_id The plan to subscribe $customer
	 */
	public function createSubscription($customerId, $planId)
	{
		return Subscription::create([
			'customer' => $customerId,
			'plan' => $planId,
		]);
	}

	public function createCustomerForUser(User $user)
	{
		$customer = Customer::create(['email' => $user->getEmail()]);
		$user->setStripeToken($customer->id);
		$this->em->flush();

		return $customer;
	}

	public function subscribeUser(User $user, $planId)
	{
		$customerId = $user->getStripeToken();
		return $this->createSubscription($customerId, $planId);
	}

	/**
	 * Create Stripe Plan
	 *
	 * Required $options keys
	 *	- name : the name of the plan
	 * 	- id : the id of the plan, needed for requests
	 * 	- amount : the amount of the plan per interval
	 *
	 * @var array $options The plan options
	 * @return \Stripe\Plan The newly created plan
	 */
	public function createPlan(array $options)
	{
		$options = $this->configurePlanOptions($options);

		$plan = Plan::create([
			'name' => $options['name'], // Basic plan
			'id' => $options['id'], // basic-monthly,
			'interval' => $options['interval'],
			'currency' => $options['currency'],
			'amount' => $options['amount'],
		]);

		return $plan;
	}

	public function getAllPlans()
	{
		return Plan::all();
	}

	public function configurePlanOptions(array $options)
	{
		$resolver = new OptionsResolver();
		$resolver->setDefaults([
			'currency' => 'usd',
			'interval' => 'month',
		]);
		$resolver->setRequired('name');
		$resolver->setRequired('id');
		$resolver->setRequired('amount');

		return $resolver->resolve($options);
	}

	public function configureCustomerOptions(array $options)
	{
		$resolver = new OptionsResolver();
		$resolver->setRequired('email');
		$resolver->setDefined('source');

		return $resolver->resolve($options);
	}

	public function configurePaymentOptions(array $options)
	{
		$resolver = new OptionsResolver();
		$resolver->setRequired('token');
		$resolver->setRequired('amount');
		$resolver->setDefaults([
			'description' => 'Blog payment',
			'currency' => 'usd',
		]);

		return $resolver->resolve($options);
	}

	public function getUser()
	{
		return $user = $this->tokenStorage->getToken()->getUser();
	}
}