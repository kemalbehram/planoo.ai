<?php

namespace BPBundle\Form;

use A2lix\TranslationFormBundle\Form\Type\TranslationsType;
use Doctrine\DBAL\Types\BooleanType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class ChargeLabelType extends AbstractType {

    /**
     * @param FormBuilderInterface $builder
     * @param array $options
     */
    public function buildForm(FormBuilderInterface $builder, array $options) {
        $builder
                ->add('translations', TranslationsType::class, ['fields' => [// [2]
                        'name' => [// [3.a]
                            'field_type' => TextType::class, // [4]
                            'label' => 'Libellé', // [4]
                            'attr' => [
                                'class' => 'form-control',
                                'row' => '50',
                                'col' => '5'
                            ],
                        ],
                    ],
                    'label' => 'Traductions',
                    'exclude_fields' => ['slug'],
                ])
                ->add('type', ChoiceType::class, [
                    'choices' => [
                        'Charge' => 'charge',
                        'Investissement' => 'investissement',
                        'Financement' => 'financement',
                    ],
                    "attr" => [
                        'class' => 'form-control'
                    ]
                ])
                ->add('classement', ChoiceType::class, [
                    'choices' => [
                        '_ _ _ _' => false,
                        'Corporel' => 'corporel',
                        'Incorporel' => 'incorporel',
                        'Financier' => 'financier',
                    ],
                    "attr" => [
                        'class' => 'form-control'
                    ]
                ])
                ->add('isInteret', ChoiceType::class, [
                    'choices' => [
                        'no' => false,
                        'yes' => true,
                    ],
                    'label' => 'Genere des interets',
                    "attr" => [
                        'class' => 'form-control'
                    ]
                ])
                ->add('isRemboursable', ChoiceType::class, [
                    'choices' => [
                        'no' => false,
                        'yes' => true,
                    ],
                    'label' => 'Subvention remboursable',
                    "attr" => [
                        'class' => 'form-control'
                    ]
                ])
                ->add('isEmpruntBancaire', ChoiceType::class, [
                    'choices' => [
                        'no' => false,
                        'yes' => true,
                    ],
                    'label' => 'Emprunt bancaire',
                    "attr" => [
                        'class' => 'form-control'
                    ]
                ])
                ->add('isCompteCourantAssocie', ChoiceType::class, [
                    'choices' => [
                        'no' => false,
                        'yes' => true,
                    ],
                    'label' => 'C. C. Associé',
                    "attr" => [
                        'class' => 'form-control'
                    ]
                ])
                ->add('isAugmentationCapital', ChoiceType::class, [
                    'choices' => [
                        'no' => false,
                        'yes' => true,
                    ],
                    'label' => 'Aug. de capital',
                    "attr" => [
                        'class' => 'form-control'
                    ]
                ])
                ->add('isDividende', ChoiceType::class, [
                    'choices' => [
                        'no' => false,
                        'yes' => true,
                    ],
                    'label' => 'Dividende',
                    "attr" => [
                        'class' => 'form-control'
                    ]
                ])
                ->add('displayTaux', ChoiceType::class, [
                    'choices' => [
                        'no' => false,
                        'yes' => true,
                    ],
                    'label' => 'Renseigner le taux ?',
                    "attr" => [
                        'class' => 'form-control'
                    ]
                ])
                ->add('displayDuree', ChoiceType::class, [
                    'choices' => [
                        'no' => false,
                        'yes' => true,
                    ],
                    'label' => 'Renseigner la durée ?',
                    "attr" => [
                        'class' => 'form-control'
                    ]
                ])
        ;
    }

    /**
     * @param OptionsResolver $resolver
     */
    public function configureOptions(OptionsResolver $resolver) {
        $resolver->setDefaults(array(
            'data_class' => 'BPBundle\Entity\ChargeLabel'
        ));
    }

}
