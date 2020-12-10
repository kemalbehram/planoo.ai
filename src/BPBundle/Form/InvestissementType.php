<?php

namespace BPBundle\Form;

use BPBundle\Repository\ChargeLabelRepository;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use BPBundle\Form\IzFloatType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\DateTimeType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class InvestissementType extends AbstractType {

    /**
     * @param FormBuilderInterface $builder
     * @param array $options
     */
    public function buildForm(FormBuilderInterface $builder, array $options) {
        $builder
                ->add("chargeLabel", EntityType::class, [
                    'label' => 'Type d\'investissement',
                    'class' => 'BPBundle:ChargeLabel',
                    'choice_label' => 'translate.name',
                    'query_builder' => function (ChargeLabelRepository $er) {
                        $type = 'investissement';
                        return $er->getChargeType($type);
                    },
                ])
                ->add('date', DateTimeType::class, [
                    'label' => 'Date estimée de l\'investissement',
                    'widget' => 'single_text',
                    'format' => 'dd/MM/yyyy',
                    'attr' => [
                        'class' => 'validate datepicker'
                    ]
                ])
                ->add('amount', IzFloatType::class, [
                    'label' => 'Montant de l\'investissements',
                ])
        ;
    }

    /**
     * @param OptionsResolver $resolver
     */
    public function configureOptions(OptionsResolver $resolver) {
        $resolver->setDefaults(array(
            'data_class' => 'BPBundle\Entity\Investissement',
            'validation_groups' => ['investissement']
        ));
    }

}
