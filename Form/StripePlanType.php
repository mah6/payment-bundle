<?php

namespace PaymentBundle\Form;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\Extension\Core\Type\IntegerType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;

use AppBundle\Intl\Currency;

class StripePlanType extends AbstractType
{
    /**
     * {@inheritdoc}
     */
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('planId', TextType::class)
            ->add('name', TextType::class)
            ->add('amount', IntegerType::class)
            ->add('currency', ChoiceType::class, [
                'choices' => Currency::getCurrencies(),
            ])
            ->add('interval', ChoiceType::class, [
                'choices' => [
                    'day' => 'day',
                    'week' => 'week',
                    'month' => 'month',
                    'year' => 'year',
                ],
            ])
            // ->add('intervalCount')
        ;
    }
    
    /**
     * {@inheritdoc}
     */
    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults(array(
            'data_class' => 'PaymentBundle\Entity\StripePlan'
        ));
    }

    /**
     * {@inheritdoc}
     */
    public function getBlockPrefix()
    {
        return 'appbundle_stripeplan';
    }


}
