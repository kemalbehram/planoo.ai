<?php

namespace BPBundle\Form;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Validator\Constraints\Valid;
use Symfony\Component\Form\Extension\Core\Type\FileType;
use Symfony\Component\Validator\Constraints\File;

class CustomWritingType extends AbstractType {

    /**
     * {@inheritdoc}
     */
    public function buildForm(FormBuilderInterface $builder, array $options) {
        $builder->add('mainService1', null, [
                    'label' => '1er principal service/produit proposé',
                    'attr' => [
                        'class' => 'validate'
                    ]
                ])
                ->add('mainService2', null, [
                    'label' => '2ème service/produit principal proposé (optionnel)',
                    'attr' => [
                        'class' => 'validate'
                    ]
                ])
                ->add('mainService3', null, [
                    'label' => '3ème service/produit principal proposé (optionnel)',
                    'attr' => [
                        'class' => 'validate'
                    ]
                ])
                ->add('mainService4', null, [
                    'label' => '4ème service/produit principal proposé (optionnel)',
                    'attr' => [
                        'class' => 'validate'
                    ]
                ])
                ->add('mainService5', null, [
                    'label' => '5ème service/produit principal proposé (optionnel)',
                    'attr' => [
                        'class' => 'validate'
                    ]
                ])
                ->add('mainProvider1', null, [
                    'label' => '1er fournisseur principal (optionnel)',
                    'attr' => [
                        'class' => 'validate'
                    ]
                ])
                ->add('mainProvider2', null, [
                    'label' => '2ème fournisseur principal (optionnel)',
                    'attr' => [
                        'class' => 'validate'
                    ]
                ])
                ->add('mainProvider3', null, [
                    'label' => '3ème fournisseur principal (optionnel)',
                    'attr' => [
                        'class' => 'validate'
                    ]
                ])
                ->add('mainPartner1', null, [
                    'label' => '1er partenaire principal (optionnel)',
                    'attr' => [
                        'class' => 'validate'
                    ]
                ])
                ->add('mainPartner2', null, [
                    'label' => '2ème partenaire principal (optionnel)',
                    'attr' => [
                        'class' => 'validate'
                    ]
                ])
                ->add('mainPartner3', null, [
                    'label' => '3ème partenaire principal (optionnel)',
                    'attr' => [
                        'class' => 'validate'
                    ]
                ])
                ->add('mainTarget1', null, [
                    'label' => '1ère cible principale',
                    'attr' => [
                        'class' => 'validate'
                    ]
                ])
                ->add('mainTarget2', null, [
                    'label' => '2ème cible principale (optionnel)',
                    'attr' => [
                        'class' => 'validate'
                    ]
                ])
                ->add('mainTarget3', null, [
                    'label' => '3ème cible principale (optionnel)',
                    'attr' => [
                        'class' => 'validate'
                    ]
                ])
                ->add('mainChannels', ChoiceType::class, [
                    'label' => 'Principaux canaux de distributions',
                    'constraints' => array(new Valid()),
                    'expanded' => true,
                    'multiple' => true,
                    'choices' => [
                        'Circuit direct',
                        'Circuit court',
                        'Circuit long',
                    ],
                ])
                ->add('marketStrategies', ChoiceType::class, [
                    'label' => 'Stratégie commerciale',
                    'constraints' => array(new Valid()),
                    'expanded' => true,
                    'multiple' => true,
                    'choices' => [
                        'La prospection',
                        'Le réseautage',
                        'La prescription',
                        'Les partenariats',
                        'Les salons, forums, expositions',
                        'L\'échantillon - La démonstration',
                        'La boutique éphémère',
                    ]
                ])
                ->add('communicationChannels', ChoiceType::class, [
                    'label' => 'Canaux de communication',
                    'constraints' => array(new Valid()),
                    'expanded' => true,
                    'multiple' => true,
                    'choices' => [
                        'Le site web',
                        'La newsletter',
                        'Les réseaux sociaux',
                        'Le bouche à oreille',
                        'Les supports papier',
                        'Les relations-presse (rp)',
                        'Le dossier de presse',
                        'Le communiqué de presse & la conférence de presse',
                        'La publicité',
                        'L’achat d’espace dans la presse écrite',
                        'La publicité en ligne & sur le lieu de vente',
                        'Communiquer sur les salons',
                        'L’événementiel',
                        'Cartes de visites',
                        'Pages jaunes',
                    ]
                ])
                ->add('mainIntermediary1', null, [
                    'label' => '1ère intermédiaire (optionnel)',
                    'attr' => [
                        'class' => 'validate'
                    ]
                ])
                ->add('mainIntermediary2', null, [
                    'label' => '2ème intermédiaire (optionnel)',
                    'attr' => [
                        'class' => 'validate'
                    ]
                ])
                ->add('mainIntermediary3', null, [
                    'label' => '3ème intermédiaire (optionnel)',
                    'attr' => [
                        'class' => 'validate'
                    ]
                ])
                ->add('cvFile', FileType::class, [
                    'label' => 'CV (pdf 10Mo max)',
                    // unmapped means that this field is not associated to any entity property
                    'mapped' => false,
                    // make it optional so you don't have to re-upload the PDF file
                    // every time you edit the Product details
                    'required' => false,
                    // unmapped fields can't define their validation using annotations
                    // in the associated entity, so you can use the PHP constraint classes
                    'constraints' => [
                        new File([
                            'maxSize' => '10M',
                            'mimeTypes' => [
                                'application/pdf',
                                'application/x-pdf',
                            ],
                            'mimeTypesMessage' => 'Merci de transmettre un fichier PDF.',
                                ])
                    ],
                ])
        ;
    }

    /**
     * {@inheritdoc}
     */
    public function configureOptions(OptionsResolver $resolver) {
        $resolver->setDefaults(array(
            'data_class' => 'BPBundle\Entity\CustomWriting'
        ));
    }

    /**
     * {@inheritdoc}
     */
    public function getBlockPrefix() {
        return 'bpbundle_customwriting';
    }

}
