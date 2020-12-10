<?php

namespace PromotionBundle\Form;

use Doctrine\ORM\EntityRepository;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\CollectionType;
use Symfony\Component\Form\Extension\Core\Type\DateTimeType;
use Symfony\Component\Form\Extension\Core\Type\IntegerType;
use Symfony\Component\Form\Extension\Core\Type\FormType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use PromotionBundle\Entity\CouponKind;
use PromotionBundle\Entity\CouponRange;

class CouponType extends AbstractType {

    /**
     * {@inheritdoc}
     */
    public function buildForm(FormBuilderInterface $builder, array $options) {
        $builder
                ->add('code', null, [
                    'label' => 'Code',
                    'attr' => [
                        'class' => 'form-control'
                    ]
                ])
                ->add('name', null, [
                    'label' => 'Nom',
                    'attr' => [
                        'class' => 'form-control'
                    ]
                ])
                ->add('nbMaxUsed', null, [
                    'label' => 'Nombre maximum d\'utilisation (0 = pas de limite)',
                    'attr' => [
                        'class' => 'form-control'
                    ]
                ])
                ->add('startsAt', DateTimeType::class, [
                    'label' => 'Date de debut',
                    'widget' => 'single_text',
                    'format' => 'dd/MM/YYYY',
                    'attr' => [
                        'class' => 'form-control datepicker',
                    ],
                    'required' => false
                ])
                ->add('endsAt', DateTimeType::class, [
                    'label' => 'Date d\'expiration',
                    'widget' => 'single_text',
                    'format' => 'dd/MM/YYYY',
                    'attr' => [
                        'class' => 'form-control datepicker',
                    ],
                    'required' => false
                ])
                ->add('range', ChoiceType::class, [
                    'label' => 'Porté',
                    'choices' => [
                        'Tout le panier' => CouponRange::CART,
                        'BP Uniquement' => CouponRange::BP_ONLY,
                        'Options uniquement' => CouponRange::OPTIONS_ONLY,
                    ]
                ])
                ->add('partner', EntityType::class, [
                    'class' => 'PartnersBundle:Partner',
                    'placeholder' => ' ',
                    'choice_label' => 'nom',
                    'required' => false
                ])
                ->add('minimumAmount', null, [
                    'label' => 'Montant minimum (sur la porté du bon)',
                    'attr' => [
                        'class' => 'form-control'
                    ]
                ])
                ->add('kind', ChoiceType::class, [
                    'label' => 'Type',
                    'choices' => [
                        'Montant fixe' => CouponKind::AMOUNT,
                        'Pourcentage' => CouponKind::PERCENT,
                    ]
                ])
                ->add('value', null, [
                    'label' => 'Montant ou Pourcentage de réduction',
                    'attr' => [
                        'class' => 'form-control'
                    ]
                ])
                ->add('nbCouponToGenerate', IntegerType::class, [
                    'label' => 'Nombre de coupons à créer',
                    'mapped' => false,
                    'data' => 1,
                    'attr' => [
                        'class' => 'form-control'
                    ]
                ])
        ;
    }

    /**
     * {@inheritdoc}
     */
    public function configureOptions(OptionsResolver $resolver) {
        $resolver->setDefaults(array(
            'data_class' => 'PromotionBundle\Entity\Coupon'
        ));
    }

    /**
     * {@inheritdoc}
     */
    public function getBlockPrefix() {
        return 'promotionbundle_coupon';
    }

}
