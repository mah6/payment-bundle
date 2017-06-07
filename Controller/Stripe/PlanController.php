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
 * @Route("/stripe/plan")
 */
class PlanController extends Controller
{
	/**
	 * @Route("/", name="stripe.plans")
	 */
	public function plansAction(Request $request)
	{
		$plans = $this->getDoctrine()->getRepository('PaymentBundle:StripePlan')->findAll();
		$stripePlan = new StripePlan();
		$form = $this->createForm(StripePlanType::class, $stripePlan, [
			'action' => $this->generateUrl('stripe.plans.create'),
		]);

		return $this->render('stripe/plans.html.twig', [
			'plans' => $plans,
			'form' => $form->createView(),
		]);
	}

	/**
	 * @Route("/create", name="stripe.plans.create")
	 */
	public function createPlanAction(Request $request)
	{
		if ($request->getMethod() != Request::METHOD_POST) return $this->redirectToRoute('stripe.plans');
		
		$stripePlan = new StripePlan();
		$form = $this->createForm(StripePlanType::class, $stripePlan);
		$form->handleRequest($request);

		if ($form->isSubmitted() && $form->isValid()) {

			$appStripe = $this->get(AppStripe::SERVICE_NAME);
			$plan = $appStripe->createPlan([
				'name' => $stripePlan->getName(),
				'id' => $stripePlan->getPlanId(),
				'amount' => $stripePlan->getAmount(),
				'currency' => $stripePlan->getCurrency(),
				'interval' => $stripePlan->getInterval(),
			]);

			$created = new \DateTime();
			$created->setTimestamp((int) $plan->created);
			$stripePlan
				->setCreated($created)
				->setIntervalCount($plan->interval_count)
			;
			$em = $this->get('doctrine.orm.entity_manager');
			$em->persist($stripePlan);
			$em->flush();
		}

		return $this->redirectToRoute('stripe.plans');
	}
}