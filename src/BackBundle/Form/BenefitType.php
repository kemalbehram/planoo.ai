<?php

namespace BackBundle\Form;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use A2lix\TranslationFormBundle\Form\Type\TranslationsType;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;

class BenefitType extends AbstractType {

    /**
     * {@inheritdoc}
     */
    public function buildForm(FormBuilderInterface $builder, array $options) {
        $builder
                ->add('translations', TranslationsType::class, [
                    'exclude_fields' => ['slug'],
                    'label' => 'General',
                    'fields' => [// [2]
                        'name' => [// [3.a]
                            'label' => 'Nom', // [4]
                            'attr' => [
                                'class' => 'form-control'
                            ],
                            'locale_options' => [// [3.b]
                                'es' => ['label' => 'nombre'], // [4]
                                'en' => ['label' => 'name'], // [4]
                                'de' => ['label' => 'name']           // [4]
                            ]
                        ],
                        'detail' => [// [3.a]
                            'field_type' => TextareaType::class,
                            'label' => 'Detail', // [4]
                            'attr' => [
                                'class' => 'tinymce form-control'
                            ],
                            'locale_options' => [// [3.b]
                                'es' => ['label' => 'Descripción'], // [4]
                                'de' => ['label' => 'Beschreibung']           // [4]
                            ]
                        ],
                        'postScriptum' => [// [3.a]
                            'label' => 'Post scriptum', // [4]
                            'attr' => [
                                'class' => 'form-control'
                            ],
                        ],
                    ]
                ])
                ->add("catalog", EntityType::class, [
                    'label' => 'Article',
                    'class' => 'PromotionBundle:Catalog',
                    'choice_label' => 'name',
                    'attr' => [
                        'class' => 'validate'
                    ]
                ])
        ;
    }

    /**
     * {@inheritdoc}
     */
    public function configureOptions(OptionsResolver $resolver) {
        $resolver->setDefaults(array(
            'data_class' => 'BackBundle\Entity\Benefit'
        ));
    }

    /**
     * {@inheritdoc}
     */
    public function getBlockPrefix() {
        return 'backbundle_benefit';
    }

}
