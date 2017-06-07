<?php

namespace PaymentBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * Customer
 *
 * @ORM\Table(name="stripe_customer")
 * @ORM\Entity(repositoryClass="PaymentBundle\Repository\StripeCustomerRepository")
 */
class StripeCustomer
{
    /**
     * @var int
     *
     * @ORM\Column(name="id", type="integer")
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="AUTO")
     */
    private $id;

    /**
     * @ORM\Column()
     */
    private $customerId;

    /**
     * @ORM\Column(nullable=true)
     */
    private $businessVatId;

    /**
     * @ORM\Column(length=3, nullable=true)
     */
    private $currency;

    /**
     * @ORM\Column(nullable=true)
     */
    private $defaultSource;

    /**
     * @ORM\Column(type="boolean", nullable=true)
     */
    private $delinquent;

    /**
     * @ORM\Column(type="text", nullable=true)
     */
    private $description;

    // TODO: add relation to Hash

    /**
     * @ORM\Column(nullable=true)
     */
    private $email;

    /**
     * @ORM\Column(type="integer", nullable=true)
     */
    private $accountBalance;

    /**
     * @ORM\Column(type="datetime", nullable=true)
     */
    private $createdAt;

    /**
     * @ORM\Column(type="boolean", nullable=true)
     */
    private $liveMode;

    /**
     * @ORM\Column(type="array", nullable=true)
     */
    private $metadata;

    /**
     * @ORM\Column(type="array", nullable=true)
     */
    private $shipping;

    /**
     * @ORM\Column(type="array", nullable=true)
     */
    private $source;

    // TODO: subscriptions relation

    /**
     * @ORM\OneToOne(targetEntity="AppBundle:User")
     */
    private $user;
}
