<?php

namespace PaymentBundle\Controller\Stripe;

use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Security;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\JsonResponse;

use PaymentBundle\Entity\Customer;
use AppBundle\Entity\User;
use PaymentBundle\Bridge\Stripe\StripeBase;
use PaymentBundle\Bridge\Stripe\CustomerBridge;
use PaymentBundle\Entity\StripeCustomer;

/**
 * @Route("/customer")
 */
class CustomerController extends Controller
{
	/**
	 * @Route("/list", name="stripe.customer.list")
	 */
	public function listAction(Request $request)
	{
		$customers = $this->getDoctrine()->getRepository("PaymentBundle:StripeCustomer")->findAll();

		return $this->render('stripe/customers.html.twig', [
			'customers' => $customers,
		]);
	}

	/**
	 * @Route("/create/{id}", name="stripe.customer.create")
	 */
	public function createCustomerAction(Request $request, User $user)
	{
		$customerService = $this->get(CustomerBridge::SERVICE_NAME);
		$customer = $customerService->createCustomer([
			'email' => $user->getEmail(),
			'metadata' => ['id' => $user->getId()]
		]);

		$appCustomer = new StripeCustomer();
		$customerService->bind($customer, $appCustomer);
		$appCustomer->setUser($user);

		$em = $this->get('doctrine.orm.entity_manager');
		$em->persist($appCustomer);
		$em->flush();

		return $this->redirectToRoute('stripe.subscription.list');
	}

	/**
	 * @Route("/delete/{id}", name="stripe.customer.delete")
	 */
	public function deleteCustomerAction(Request $request, User $user)
	{
		return $this->redirectToRoute('stripe.subscription.list');
	}

}