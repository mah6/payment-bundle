<?php

namespace PaymentBundle\Controller\Stripe;

use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Security;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\JsonResponse;

use PaymentBundle\Entity\Customer;

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
		return $this->render('stripe/customers.html.twig', [
			'customers' => [],
		]);
	}
}