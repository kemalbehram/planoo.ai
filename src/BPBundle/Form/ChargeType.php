<?php

namespace BPBundle\Form;

use Symfony\Component\Form\AbstractType;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\Extension\Core\Type\HiddenType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\Extension\Core\Type\CollectionType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use BPBundle\Repository\ChargeLabelRepository;
use Symfony\Component\Form\FormEvents;
use Symfony\Component\Form\FormEvent;
use Symfony\Component\Form\FormInterface;

class ChargeType extends AbstractType {

    /**
     * @param FormBuilderInterface $builder
     * @param array $options
     */
    public function buildForm(FormBuilderInterface $builder, array $options) {

        $builder->add('tva', ChoiceType::class, [
            'choices' => [
                ''=>null,
                '20%' => 20,
                '10%' => 10,
                '5,5%' => 5.5,
                '2,1%' => 2.1
            ],
            'label' => 'TVA récupérable (%) (Laisser vide pour utiliser le taux de TVA par défaut du Business Plan)',
            'attr' => [
                'class' => 'form-control',
            ]
        ])->add('providerDelay', null, [
            'label' => 'Délai de paiement théorique des fournisseurs (en nbre de jours) (Laisser vide pour utiliser le délai par défaut du Business Plan)',
            'required' => false,
            'attr' => [
                'class' => 'validate',
                'min' => 0
            ]
        ])->add('termeEchu', ChoiceType::class, [
            'choices' => [
                'Paiement en fin de période' => true,
                'Paiement en début de période' => false,
            ],
            'label' => 'Terme',
            "attr" => [
                'class' => 'form-control'
            ]
        ])->add('periodicite', ChoiceType::class, [
            'choices' => [
                'Mensuelle' => 1,
                'Bimensuelle' => 2,
                'Trimestrielle' => 3,
                'Semestrielle' => 6,
                'Annuelle' => 12,
            ],
            'label' => 'Périodicité',
            "attr" => [
                'class' => 'form-control'
            ]
        ]);

        $formModifierTaux = function (FormInterface $form, $isTaux = false) {
            if ($isTaux) {
                $form->add('isTaux', HiddenType::class, ['mapped' => false, 'data' => true]);
                $form->add('taux', IzFloatType::class, [
                    'label' => 'Pourcentage du Chiffre d\'affaire (%)',
                    'required' => true,
                ]);
            } else {
                $form->add('isTaux', HiddenType::class, ['mapped' => false, 'data' => false]);
                $form->add('infoCharges', CollectionType::class, [
                    'entry_type' => InfoChargeType::class,
                ]);
            }
        };

        $formModifierLabel = function (FormInterface $form, $isCustomLabel = false) {
            if ($isCustomLabel) {
                $form->add('isCustomLabel', HiddenType::class, ['mapped' => false, 'data' => true]);
                $form->add('otherChoice', null, [
                    'label' => 'Autre choix',
                    'required' => true,
                    'attr' => [
                        'class' => 'validate',
                        'placeholder' => 'Autre choix'
                    ]
                ]);
            } else {
                $form->add('isCustomLabel', HiddenType::class, ['mapped' => false, 'data' => false]);
                $form->add('chargeLabel', EntityType::class, [
                    'label' => 'Type de la dépense',
                    'class' => "BPBundle:ChargeLabel",
                    'choice_label' => 'translate.name',
                    'query_builder' => function (ChargeLabelRepository $er) {
                        $type = 'charge';
                        return $er->getChargeType($type);
                    }
                ]);
            }
        };

        //set form type when loading new or edit form
        $builder->addEventListener(
                FormEvents::PRE_SET_DATA, function (FormEvent $event) use ($formModifierTaux, $formModifierLabel, $options) {


            $charge = $event->getData();

            $isTaux = false;
            if (isset($options['attr']['isTaux'])) {
                $isTaux = $event->getForm()->getConfig()->getOptions()['attr']['isTaux'];
            } elseif ($charge->getTaux() != null) {
                $isTaux = true;
            }
            $formModifierTaux($event->getForm(), $isTaux);

            $isCustomLabel = false;
            if (isset($options['attr']['isCustomLabel'])) {
                $isCustomLabel = $event->getForm()->getConfig()->getOptions()['attr']['isCustomLabel'];
            } elseif ($charge->getOtherChoice() != null) {
                $isCustomLabel = true;
            }
            $formModifierLabel($event->getForm(), $isCustomLabel);
        }
        );

        $builder->addEventListener(
                FormEvents::PRE_SUBMIT, function (FormEvent $event) use ($formModifierTaux, $formModifierLabel) {

            $isTaux = $event->getForm()->getConfig()->getOptions()['attr']['isTaux'];
            $formModifierTaux($event->getForm(), $isTaux);
            $isCustomLabel = $event->getForm()->getConfig()->getOptions()['attr']['isCustomLabel'];
            $formModifierLabel($event->getForm(), $isCustomLabel);
        }
        );

        $builder->addEventListener(
                FormEvents::POST_SUBMIT, function (FormEvent $event) {

            $isTaux = $event->getForm()->getConfig()->getOptions()['attr']['isTaux'];
            $isCustomLabel = $event->getForm()->getConfig()->getOptions()['attr']['isCustomLabel'];


            $formData = $event->getForm()->getData();
            if ($isTaux) {
                //$formData->setInfoCharges(null);
            } else {
                $formData->setTaux(null);
            }
            if ($isCustomLabel) {
                $formData->setChargeLabel(null);
            } else {
                $formData->setOtherChoice(null);
            }
        }
        );
    }

    /**
     * @param OptionsResolver $resolver
     */
    public function configureOptions(OptionsResolver $resolver) {
        $resolver->setDefaults(array(
            'data_class' => 'BPBundle\Entity\Charge'
        ));
    }

    /**
     * {@inheritdoc}
     */
    public function getBlockPrefix() {
        return 'bpbundle_charge';
    }

}
