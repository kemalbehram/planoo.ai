<?php

namespace BackBundle\Form;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use A2lix\TranslationFormBundle\Form\Type\TranslationsType;

class PageType extends AbstractType
{
    /**
     * @param FormBuilderInterface $builder
     * @param array $options
     */
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('translations', TranslationsType::class, [
                'exclude_fields' => ['slug'],
                'label' => 'General',
                'fields' => [                               // [2]
                    'title' => [                         // [3.a]
                        'label' => 'Titre',                    // [4]
                        'attr' => [
                            'class'=>'form-control'
                        ],
                        'locale_options' => [                  // [3.b]
                            'es' => ['label' => 'título'],     // [4]
                            'en' => ['label' => 'title'],          // [4]
                            'de' => ['label' => 'Titel']           // [4]
                        ]
                    ],
                    'content' => [                         // [3.a]
                        'field_type' => TextareaType::class,                // [4]
                        'label' => 'Description',                    // [4]
                        'attr' => [
                            'class'=>'tinymce form-control'
                        ],
                        'locale_options' => [                  // [3.b]
                            'es' => ['label' => 'Descripción'],     // [4]
//                                'de' => ['display' => false]           // [4]
                            'de' => ['label' => 'Beschreibung']           // [4]
                        ]
                    ],
                ]
            ])
            ->add('file', null, [
                'label' => 'Image',
                'attr' => [
                    'class' => 'form-control'
                ]
            ])
        ;
    }
    
    /**
     * @param OptionsResolver $resolver
     */
    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults(array(
            'data_class' => 'BackBundle\Entity\Page'
        ));
    }
}
