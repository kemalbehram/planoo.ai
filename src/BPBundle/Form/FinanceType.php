<?php

namespace BPBundle\Form;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\DateTimeType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\Extension\Core\Type\IntegerType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class FinanceType extends AbstractType {

    /**
     * @param FormBuilderInterface $builder
     * @param array $options
     */
    public function buildForm(FormBuilderInterface $builder, array $options) {
        $builder
                ->add('capital', IntegerType::class, [
                    'label' => 'Montant du capital social en euros',
                    'attr' => [
                        'class' => 'form-control validate',
                        'min' => 1,
                    ]
                ])
                ->add('accountingPeriod', null, [
                    'label' => 'Nombre d\'années du businness plan (Nombre d\'exercices comptables)',
                    'disabled' => isset($options['attr']['fixedAccountingPeriod']),
                    'attr' => [
                        'class' => 'validate',
                        'min' => 3,
                        'max' => 5
                    ] 
                ])
                ->add('createdAt', DateTimeType::class, [
                    'label' => 'Date de création',
                    'widget' => 'single_text',
                    'format' => 'dd/MM/yyyy',
                    'attr' => [
                        'class' => 'validate datepicker',
                        'pattern' => '(0[1-9]|[12][0-9]|3[01])[- /.](0[1-9]|1[012])[- /.](19|20)\d\d'
                    ]
                ])
                ->add('closingDate', DateTimeType::class, [
                    'label' => 'Date de clôture de 1er exercice',
                    'widget' => 'single_text',
                    'format' => 'dd/MM/yyyy',
                    'attr' => [
                        'class' => 'validate datepicker',
                        'pattern' => '(0[1-9]|[12][0-9]|3[01])[- /.](0[1-9]|1[012])[- /.](19|20)\d\d'
                    ]
                ])
                ->add('tva', ChoiceType::class, [
                    'choices' => [
                        '20%' => 20,
                        '10%' => 10,
                        '5,5%' => 5.5,
                        '2,1%' => 2.1
                    ],
                    'label' => 'TVA (%)',
                    'attr' => [
                        'class' => 'form-control'
                    ]
                ])
                ->add('tvaSurEncaissement', ChoiceType::class, [
                    'choices' => [
                        'Sur les débits' => false,
                        'Sur les encaissements' => true,
                    ],
                    'label' => 'Mode de TVA',
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
            'data_class' => 'BPBundle\Entity\Information',
            'validation_groups' => ['finance']
        ));
    }

}
