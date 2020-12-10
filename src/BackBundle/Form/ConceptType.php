<?php

namespace BackBundle\Form;

use A2lix\TranslationFormBundle\Form\Type\TranslationsType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class ConceptType extends AbstractType
{
    /**
     * {@inheritdoc}
     */
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('translations', TranslationsType::class, [
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
                ]
            ])
        ;
    }
    
    /**
     * {@inheritdoc}
     */
    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults(array(
            'data_class' => 'BackBundle\Entity\Concept'
        ));
    }

    /**
     * {@inheritdoc}
     */
    public function getBlockPrefix()
    {
        return 'backbundle_concept';
    }


}
