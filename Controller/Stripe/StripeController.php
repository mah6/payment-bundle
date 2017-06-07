<?php

namespace PaymentBundle\Controller\Stripe;

use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Security;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\JsonResponse;

use PaymentBundle\Service\AppStripe;
use AppBundle\Entity\User;
use PaymentBundle\Entity\StripePlan;
use PaymentBundle\Form\StripePlanType;

/**
 * @Route("/stripe")
 */
class StripeController extends Controller
{
	/**
	 * @Route("/checkout", name="stripe.checkout")
	 * @Security("has_role('ROLE_USER')")
	 */
	public function checkoutAction(Request $request)
	{
		$stripeKeys = $this->container->getParameter('stripe');

		if ($request->getMethod() == Request::METHOD_POST) {

			$token = $request->request->get('stripeToken');
			$stripe = $this->get(AppStripe::SERVICE_NAME);
			// $charge = $stripe->pay(['amount' => 1999, 'token' => $token]);
			$charge = $stripe->saveCustomerCard([
				'amount' => 23900,
				'token' => $token,
			]);

			dump($charge);

			return new Response('<body><label>Token</label> : ' . $token . '</body>');
		}

		return $this->render('stripe/checkout.html.twig', [
			'pk' => $stripeKeys['publishable_key'],
		]);
	}

	/**
	 * @Route("/subscriptions", name="stripe.subscriptions")
	 */
	public function subscriptionsAction(Request $request)
	{
		$appStripe = $this->get(AppStripe::SERVICE_NAME);
		$users = $this->getDoctrine()->getRepository('AppBundle:User')->findAll();
		$plans = $appStripe->getAllPlans();

		return $this->render('stripe/subscriptions.html.twig', [
			'users' => $users,
			'plans' => $plans,
		]);
	}

	/**
	 * @Route("/customer/create/{id}", name="stripe.customer.create")
	 */
	public function createCustomerAction(Request $request, User $user)
	{
		$appStripe = $this->get(AppStripe::SERVICE_NAME);
		$appStripe->createCustomerForUser($user);

		return $this->redirectToRoute('stripe.subscriptions');
	}

	/**
	 * @Route("/customer/delete/{id}", name="stripe.customer.delete")
	 */
	public function deleteCustomerAction(Request $request, User $user)
	{
		return $this->redirectToRoute('stripe.subscriptions');
	}

	/**
	 * @Route("/subscription/subscribe-to-plan/{id}/{planId}", name="stripe.subscribe_to_plan")
	 */
	public function subscribeToPlanaction(Request $request, User $user, $planId)
	{
		$appStripe = $this->get(AppStripe::SERVICE_NAME);
		dump($appStripe->subscribeUser($user, $planId));

		return new Response('<body>Subscription created</body>');

		return $this->redirectToRoute('stripe.subscriptions');
	}

}