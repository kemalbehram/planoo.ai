<?php

namespace BPBundle\Form;

use BPBundle\Repository\ChargeLabelRepository;
use BPBundle\Form\IzFloatType;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\DateTimeType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Form\FormEvents;
use Symfony\Component\Form\FormEvent;
use Symfony\Component\Form\FormInterface;
use Symfony\Component\Form\Extension\Core\Type\HiddenType;

class FundingType extends AbstractType {

    /**
     * @param FormBuilderInterface $builder
     * @param array $options
     */
    public function buildForm(FormBuilderInterface $builder, array $options) {


        $formModifier = function (FormInterface $form, $data) {

            $displayTaux = false;
            $displayDuree = false;

            if (is_array($data) && $data['chargeLabel'] && $form->getConfig()->getOptions()['entityManager']) {
                $em = $form->getConfig()->getOptions()['entityManager'];
                $chargeLabel = $em->getRepository('BPBundle:ChargeLabel')->find($data['chargeLabel']);

                $displayTaux = $chargeLabel->getDisplayTaux();
                $displayDuree = $chargeLabel->getDisplayDuree();
            } elseif (!is_array($data) && $data->getChargeLabel()) {
                $displayTaux = $data->getChargeLabel()->getDisplayTaux();
                $displayDuree = $data->getChargeLabel()->getDisplayDuree();
            }

            if (!$displayTaux) {
                $form->remove('rate');
            } elseif (!$form->has('rate')) {
                $form->add('rate', IzFloatType::class, [
                    'label' => 'Taux (%)',
                ]);
            }

            if (!$displayDuree) {
                $form->remove('within');
                $form->remove('differe');
            } elseif (!$form->has('differe')) {
                $form->add('differe', null, [
                            'label' => 'Différé (en nombre de mois)',
                            'attr' => [
                                'class' => 'form-control validate',
                                'min' => 1
                            ]
                        ])
                        ->add('within', null, [
                            'label' => 'Durée (en nombre de mois)',
                            'attr' => [
                                'class' => 'form-control validate',
                                'min' => 1
                            ]
                ]);
            }
        };


        $builder->addEventListener(
                FormEvents::POST_SET_DATA, function (FormEvent $event) use ($formModifier) {
            $formModifier($event->getForm(), $event->getForm()->getData());
        });

        $builder->addEventListener(
                FormEvents::PRE_SUBMIT, function (FormEvent $event) use ($formModifier) {
            $formModifier($event->getForm(), $event->getData());
        });

        $builder->addEventListener(
                FormEvents::POST_SUBMIT, function (FormEvent $event) {

            //during this event unset data are reset to default to be updated in database. If this isn't done, old values persists

            $formData = $event->getData();
            if ($event->getData()->getChargeLabel() && !$event->getData()->getChargeLabel()->getDisplayTaux()) {
                $formData->setRate(0);
            }
            if ($event->getData()->getChargeLabel() && !$event->getData()->getChargeLabel()->getDisplayDuree()) {
                $formData->setWithin(1);
                $formData->setDiffere(null);
            }
        }
        );

        $builder
                ->add("id", HiddenType::class, [])
                ->add("chargeLabel", EntityType::class, [
                    'class' => 'BPBundle:ChargeLabel',
                    'label' => 'Type de financement',
                    'choice_label' => 'translate.name',
                    'query_builder' => function (ChargeLabelRepository $er) {
                        $type = 'financement';
                        return $er->getChargeType($type);
                    },
                ])
                ->add('createdAt', DateTimeType::class, [
                    'label' => 'Date de souscription',
                    'widget' => 'single_text',
                    'format' => 'dd/MM/yyyy',
                    'attr' => [
                        'class' => 'form-control validate datepicker'
                    ]
                ])
                ->add('amount', IzFloatType::class, [
                    'label' => 'Montant',
                    'attr' => [
                        'class' => 'form-control validate',
                        'pattern' => '[1-9](\d+)?([\.|,]\d{1,2})?'
                    ]
                ])
                ->add('rate', IzFloatType::class, [
                    'label' => 'Taux (%)',
                ])
                ->add('differe', null, [
                    'label' => 'Différé (en nombre de mois)',
                    'attr' => [
                        'class' => 'form-control validate',
                        'min' => 1
                    ]
                ])
                ->add('within', null, [
                    'label' => 'Durée (en nombre de mois)',
                    'attr' => [
                        'class' => 'form-control validate',
                        'min' => 1
                    ]
        ]);
    }

    /**
     * @param OptionsResolver $resolver
     */
    public function configureOptions(OptionsResolver $resolver) {
        $resolver->setDefaults(array(
            'data_class' => 'BPBundle\Entity\Funding',
            'validation_groups' => ['funding'],
            'entityManager' => null
        ));
    }

}
