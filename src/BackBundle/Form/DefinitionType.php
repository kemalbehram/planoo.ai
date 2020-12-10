<?php

namespace BackBundle\Form;

use A2lix\TranslationFormBundle\Form\Type\TranslationsType;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\CollectionType;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class DefinitionType extends AbstractType {

    /**
     * {@inheritdoc}
     */
    public function buildForm(FormBuilderInterface $builder, array $options) {
        $builder
                ->add('concept', EntityType::class, array(
                    // query choices from this entity
                    'class' => 'BackBundle:Concept',
                    // use the User.username property as the visible option string
                    'choice_label' => 'id',
                        // used to render a select box, check boxes or radios
                        // 'multiple' => true,
                        // 'expanded' => true,
                ))
                ->add('translations', TranslationsType::class, [
                    'label' => 'General',
                    'fields' => [// [2]
                        'name' => [// [3.a]
                            'label' => 'Nom', // [4]
                            'attr' => [
                                'class' => 'form-control'
                            ],
                            'locale_options' => [// [3.b]
                                'es' => ['label' => 'Apellido'], // [4]
                                'en' => ['label' => 'Name'], // [4]
                                'de' => ['label' => 'Name']           // [4]
                            ]
                        ],
                        'content' => [// [3.a]
                            'field_type' => TextareaType::class, // [4]
                            'label' => 'Description', // [4]
                            'attr' => [
                                'class' => 'tinymce form-control'
                            ],
                            'locale_options' => [// [3.b]
                                'es' => ['label' => 'Descripción'], // [4]
//                                'de' => ['display' => false]           // [4]
                                'de' => ['label' => 'Beschreibung']           // [4]
                            ]
                        ],
                    ]
                ])
        ;
    }

    /**
     * {@inheritdoc}
     */
    public function configureOptions(OptionsResolver $resolver) {
        $resolver->setDefaults(array(
            'data_class' => 'BackBundle\Entity\Definition'
        ));
    }

    /**
     * {@inheritdoc}
     */
    public function getBlockPrefix() {
        return 'backbundle_definition';
    }

}
