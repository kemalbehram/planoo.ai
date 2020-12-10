<?php

namespace BPBundle\Form;

use Symfony\Component\Form\FormEvent;
use Symfony\Component\Form\FormEvents;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormInterface;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\Validator\Constraints\Valid;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\Extension\Core\Type\HiddenType;
use Symfony\Component\Form\Extension\Core\Type\CollectionType;

class ProduitType extends AbstractType {

    /**
     * @param FormBuilderInterface $builder
     * @param array $options
     */
    public function buildForm(FormBuilderInterface $builder, array $options) {

        $formModifier = function (FormInterface $form, $isAdvanceMode = false) {
            if ($isAdvanceMode) {
                $form->add('isAdvanceMode', HiddenType::class, ['mapped' => false, 'data' => true]);
                $form->add('infoProduct', CollectionType::class, [
                    'entry_options' => [
                        'attr' => [
                            'isAdvanceMode' => true
                        ]
                    ],
                    'entry_type' => InfoProductType::class,
                    'constraints' => array(new Valid())
                ]);
                $form->add('tvaVentes', ChoiceType::class, [
                    'choices' => [
                        ''=>null,
                        '20%' => 20,
                        '10%' => 10,
                        '5,5%' => 5.5,
                        '2,1%' => 2.1
                    ],
                    'label' => 'TVA spécifique applicable sur les ventes(%) (Laisser vide pour utiliser le taux par défaut du Business Plan)',
                    'attr' => [
                        'class' => 'form-control',
                    ]
                ]);

                $form->add('tvaAchats', ChoiceType::class, [
                    'choices' => [
                        ''=>null,
                        '20%' => 20,
                        '10%' => 10,
                        '5,5%' => 5.5,
                        '2,1%' => 2.1
                    ],
                    'label' => 'TVA spécifique récupérable sur les achats de marchandise (%) (Laisser vide pour utiliser le taux par défaut du Business Plan)',
                    'attr' => [
                        'class' => 'form-control',
                    ]
                ]);

                $form->add('customerDelay', IzFloatType::class, [
                    'label' => 'Délai de paiement théorique des clients (en jours) (Laisser vide pour utiliser le taux par défaut du Business Plan)',
                    'required' => false
                ]);
            } else {
                $form->add('isAdvanceMode', HiddenType::class, ['mapped' => false, 'data' => false]);
                $form->add('infoProduct', CollectionType::class, [
                    'entry_options' => [
                        'attr' => [
                            'isAdvanceMode' => false
                        ]
                    ],
                    'entry_type' => InfoProductType::class,
                    'constraints' => array(new Valid()),
                ]);
            }
        };

        $builder
                ->add('name', null, [
                    'label' => 'Nom du produit/service',
                    'attr' => [
                        'class' => 'validate'
                    ]
                ])
                ->add('nameCommission', null, [
                    'label' => 'Nom de substitution pour les commissions versées, exemple : apport d’affaires (Facultatif)',
                    'attr' => [
                        'class' => 'validate'
                    ]
                ])
        ;

        //advance mode has to be activated if nbVente has a value, other with simple mode has to be loaded
        //set form type when loading new or edit form
        $builder->addEventListener(
                FormEvents::PRE_SET_DATA, function (FormEvent $event) use ($formModifier, $options) {

            $isAdvanceMode = false;
            if (isset($options['attr']['isAdvanceMode'])) {
                $isAdvanceMode = $options['attr']['isAdvanceMode'];
            } else {
                $produit = $event->getData();
                $infoProducts = $produit->getInfoProduct();

                if ($infoProducts != null) {
                    foreach ($infoProducts as $infoProduct) {
                        $isAdvanceMode = $isAdvanceMode || $infoProduct->getNbVente() != null;
                    }
                }
            }
            $formModifier($event->getForm(), $isAdvanceMode);
        });

        $builder->addEventListener(
                FormEvents::PRE_SUBMIT, function (FormEvent $event) use ($formModifier, $options) {

            $isAdvanceMode = false;
            if (isset($options['attr']['isAdvanceMode'])) {
                $isAdvanceMode = $options['attr']['isAdvanceMode'];
            } elseif (isset($event->getData()['isAdvanceMode'])) {
                $isAdvanceMode = $event->getData()['isAdvanceMode'];
            }
            $formModifier($event->getForm(), $isAdvanceMode);
        });
    }

    /**
     * @param OptionsResolver $resolver
     */
    public function configureOptions(OptionsResolver $resolver) {
        $resolver->setDefaults(array(
            'data_class' => 'BPBundle\Entity\Produit',
            'validation_groups' => ['income']
        ));
    }

}
