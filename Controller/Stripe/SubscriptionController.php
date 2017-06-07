<?php

namespace PaymentBundle\Controller\Stripe;

use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Security;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\JsonResponse;

use AppBundle\Entity\User;
use PaymentBundle\Bridge\Stripe\SubscriptionBridge;

/**
 * @Route("/stripe/subscription")
 */
class SubscriptionController extends Controller
{
	/**
	 * @Route("/list", name="stripe.subscription.list")
	 */
	public function subscriptionsAction(Request $request)
	{
		$appStripe = $this->get(SubscriptionBridge::SERVICE_NAME);
		$users = $this->getDoctrine()->getRepository('AppBundle:User')->findAll();
		$plans = $this->getDoctrine()->getRepository('PaymentBundle:StripePlan')->findAll();

		return $this->render('stripe/subscriptions.html.twig', [
			'users' => $users,
			'plans' => $plans,
		]);
	}
}