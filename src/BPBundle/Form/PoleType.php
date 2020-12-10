<?php

namespace BPBundle\Form;

use A2lix\TranslationFormBundle\Form\Type\TranslationsType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class PoleType extends AbstractType
{


    /**
     * @param FormBuilderInterface $builder
     * @param array $options
     */
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('translations', TranslationsType::class,
                [
                    'label' => 'Traductions',
                    'fields' => [                               // [2]
                        'name' => [                         // [3.a]
                            'label' => 'Nom',                    // [4]
                            'attr' => [
                                'class'=>'form-control'
                            ],
                            'locale_options' => [                  // [3.b]
                                'es' => ['label' => 'título'],     // [4]
                                'en' => ['label' => 'title'],          // [4]
                                'de' => ['label' => 'Titel']           // [4]
                            ]
                        ]
                    ],
                ])
        ;
    }


    /**
     * @param OptionsResolver $resolver
     */
    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults(array(
            'data_class' => 'BPBundle\Entity\Pole'
        ));
    }
}
