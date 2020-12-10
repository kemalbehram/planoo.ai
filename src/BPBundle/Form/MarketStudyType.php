<?php

namespace BPBundle\Form;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\Extension\Core\Type\IntegerType;
use Symfony\Component\Validator\Constraints\Valid;
use Symfony\Component\Form\Extension\Core\Type\FileType;
use Symfony\Component\Validator\Constraints\File;

class MarketStudyType extends AbstractType {

    /**
     * {@inheritdoc}
     */
    public function buildForm(FormBuilderInterface $builder, array $options) {
        $builder->add('mainMarket', null, [
                    'label' => 'Marché principal',
                    'attr' => [
                        'class' => 'validate'
                    ]
                ])->add('marketSize', ChoiceType::class, [
                    'label' => 'Taille du marché',
                    'constraints' => array(new Valid()),
                    'choices' => [
                        'Ville' => 'Ville',
                        'Département' => 'Département',
                        'Région' => 'Région',
                        'Pays' => 'Pays',
                        'Continent' => 'Continent',
                        'Monde' => 'Monde',
                    ],
                ])->add("addressMarketPlace", AddressType::class, [
                    'label' => false,
                    'constraints' => array(new Valid()),
                    'required' => false,
                ])
                ->add('hasMarketPlace', ChoiceType::class, [
                    'label' => "null",
                    'choices' => [
                        'Non' => 0,
                        'Oui' => 1
                    ]
                ])->add('transportMode', ChoiceType::class, [
                    'label' => 'Mode de transport',
                    'constraints' => array(new Valid()),
                    'choices' => [
                        'A pied' => 'A pied',
                        'En vélo' => 'En vélo',
                        'En voiture' => 'En voiture',
                        'En transports en commun' => 'En transports en commun',
                        'En camion' => 'En camion'
                    ]
                ])->add('transportDurationHours', IntegerType::class, [
                    'label' => 'Temps de trajet - Heures',
                    'required' => false,
                    'attr' => [
                        'class' => 'form-control validate',
                    ]
                ])->add('transportDurationMinutes', IntegerType::class, [
                    'label' => 'Temps de trajet - Minutes',
                    'required' => false,
                    'attr' => [
                        'class' => 'form-control validate',
                    ]
                ])
        ;
    }

    /**
     * {@inheritdoc}
     */
    public function configureOptions(OptionsResolver $resolver) {
        $resolver->setDefaults(array(
            'data_class' => 'BPBundle\Entity\MarketStudy'
        ));
    }

    /**
     * {@inheritdoc}
     */
    public function getBlockPrefix() {
        return 'bpbundle_marketstudy';
    }

}
