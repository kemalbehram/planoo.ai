<?php

namespace PaymentBundle\Form;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\HiddenType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class PaymentType extends AbstractType {

    /**
     * @param FormBuilderInterface $builder
     * @param array $options
     */
    public function buildForm(FormBuilderInterface $builder, array $options) {
        $builder
                ->add('card', null, [
                    'label' => false,
                    'attr' => [
                        'data-stripe' => 'number',
                        'placeholder' => 'Numéro de carte',
                        'class' => 'form-control validate'
                    ]
                ])
                ->add('name', null, [
                    'label' => false,
                    'attr' => [
                        'data-stripe' => 'name',
                        'placeholder' => 'Nom du porteur',
                        'class' => 'form-control validate'
                    ]
                ])
                ->add('month', null, [
                    'label' => false,
                    'attr' => [
                        'data-stripe' => 'exp_month',
                        'placeholder' => 'Mois d\'expiration',
                        'class' => 'form-control validate'
                    ]
                ])
                ->add('year', null, [
                    'label' => false,
                    'attr' => [
                        'data-stripe' => 'exp_year',
                        'placeholder' => '/      Année d\'expiration',
                        'class' => 'form-control validate'
                    ]
                ])
                ->add('cvc', null, [
                    'label' => false,
                    'attr' => [
                        'data-stripe' => 'cvc',
                        'placeholder' => 'CVC',
                        'class' => 'form-control validate'
                    ]
                ])
                ->add('stripeToken', HiddenType::class, [
                    'attr' => [
                        'data-stripe' => 'token_stripe'
                    ]
                ])
                ->add('amount', HiddenType::class, [
                    'attr' => [
                        'data-cart' => 'amount'
                    ]
                ])
                ->add('cart', HiddenType::class, [
                    'attr' => [
                        'data-cart' => 'command'
                    ]
                ])
        ;
    }

    /**
     * @param OptionsResolver $resolver
     */
    public function configureOptions(OptionsResolver $resolver) {
        $resolver->setDefaults(array(
            'data_class' => 'PaymentBundle\Entity\Payment'
        ));
    }

}
