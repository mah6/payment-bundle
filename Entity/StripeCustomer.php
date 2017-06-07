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
     * @ORM\OneToOne(targetEntity="AppBundle\Entity\User")
     */
    private $user;


    /**
     * Get id
     *
     * @return integer
     */
    public function getId()
    {
        return $this->id;
    }

    /**
     * Set customerId
     *
     * @param string $customerId
     *
     * @return StripeCustomer
     */
    public function setCustomerId($customerId)
    {
        $this->customerId = $customerId;

        return $this;
    }

    /**
     * Get customerId
     *
     * @return string
     */
    public function getCustomerId()
    {
        return $this->customerId;
    }

    /**
     * Set businessVatId
     *
     * @param string $businessVatId
     *
     * @return StripeCustomer
     */
    public function setBusinessVatId($businessVatId)
    {
        $this->businessVatId = $businessVatId;

        return $this;
    }

    /**
     * Get businessVatId
     *
     * @return string
     */
    public function getBusinessVatId()
    {
        return $this->businessVatId;
    }

    /**
     * Set currency
     *
     * @param string $currency
     *
     * @return StripeCustomer
     */
    public function setCurrency($currency)
    {
        $this->currency = $currency;

        return $this;
    }

    /**
     * Get currency
     *
     * @return string
     */
    public function getCurrency()
    {
        return $this->currency;
    }

    /**
     * Set defaultSource
     *
     * @param string $defaultSource
     *
     * @return StripeCustomer
     */
    public function setDefaultSource($defaultSource)
    {
        $this->defaultSource = $defaultSource;

        return $this;
    }

    /**
     * Get defaultSource
     *
     * @return string
     */
    public function getDefaultSource()
    {
        return $this->defaultSource;
    }

    /**
     * Set delinquent
     *
     * @param boolean $delinquent
     *
     * @return StripeCustomer
     */
    public function setDelinquent($delinquent)
    {
        $this->delinquent = $delinquent;

        return $this;
    }

    /**
     * Get delinquent
     *
     * @return boolean
     */
    public function getDelinquent()
    {
        return $this->delinquent;
    }

    /**
     * Set description
     *
     * @param string $description
     *
     * @return StripeCustomer
     */
    public function setDescription($description)
    {
        $this->description = $description;

        return $this;
    }

    /**
     * Get description
     *
     * @return string
     */
    public function getDescription()
    {
        return $this->description;
    }

    /**
     * Set email
     *
     * @param string $email
     *
     * @return StripeCustomer
     */
    public function setEmail($email)
    {
        $this->email = $email;

        return $this;
    }

    /**
     * Get email
     *
     * @return string
     */
    public function getEmail()
    {
        return $this->email;
    }

    /**
     * Set accountBalance
     *
     * @param integer $accountBalance
     *
     * @return StripeCustomer
     */
    public function setAccountBalance($accountBalance)
    {
        $this->accountBalance = $accountBalance;

        return $this;
    }

    /**
     * Get accountBalance
     *
     * @return integer
     */
    public function getAccountBalance()
    {
        return $this->accountBalance;
    }

    /**
     * Set createdAt
     *
     * @param \DateTime $createdAt
     *
     * @return StripeCustomer
     */
    public function setCreatedAt($createdAt)
    {
        $this->createdAt = $createdAt;

        return $this;
    }

    /**
     * Get createdAt
     *
     * @return \DateTime
     */
    public function getCreatedAt()
    {
        return $this->createdAt;
    }

    /**
     * Set liveMode
     *
     * @param boolean $liveMode
     *
     * @return StripeCustomer
     */
    public function setLiveMode($liveMode)
    {
        $this->liveMode = $liveMode;

        return $this;
    }

    /**
     * Get liveMode
     *
     * @return boolean
     */
    public function getLiveMode()
    {
        return $this->liveMode;
    }

    /**
     * Set metadata
     *
     * @param array $metadata
     *
     * @return StripeCustomer
     */
    public function setMetadata($metadata)
    {
        $this->metadata = $metadata;

        return $this;
    }

    /**
     * Get metadata
     *
     * @return array
     */
    public function getMetadata()
    {
        return $this->metadata;
    }

    /**
     * Set shipping
     *
     * @param array $shipping
     *
     * @return StripeCustomer
     */
    public function setShipping($shipping)
    {
        $this->shipping = $shipping;

        return $this;
    }

    /**
     * Get shipping
     *
     * @return array
     */
    public function getShipping()
    {
        return $this->shipping;
    }

    /**
     * Set source
     *
     * @param array $source
     *
     * @return StripeCustomer
     */
    public function setSource($source)
    {
        $this->source = $source;

        return $this;
    }

    /**
     * Get source
     *
     * @return array
     */
    public function getSource()
    {
        return $this->source;
    }

    /**
     * Set user
     *
     * @param \AppBundle\Entity\User $user
     *
     * @return StripeCustomer
     */
    public function setUser(\AppBundle\Entity\User $user = null)
    {
        $this->user = $user;
        $user->setCustomer($this);

        return $this;
    }

    /**
     * Get user
     *
     * @return \AppBundle\Entity\User
     */
    public function getUser()
    {
        return $this->user;
    }
}
