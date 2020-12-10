<?php

namespace BPBundle\Form;

use Doctrine\ORM\EntityRepository;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use BPBundle\Form\IzFloatType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\DateTimeType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class StaffType extends AbstractType {

    /**
     * @param FormBuilderInterface $builder
     * @param array $options
     */
    public function buildForm(FormBuilderInterface $builder, array $options) {
        $builder
                ->add('name', null, [
                    'label' => 'Libellé court (Facultatif)',
                    'attr' => [
                        'class' => 'validate'
                    ]
                ])
                ->add("pole", EntityType::class, [
                    'class' => 'BPBundle:Pole',
                    'choice_label' => 'translate.name',
                    'query_builder' => function (EntityRepository $er) {
                        return $er->myFindAll();
                    },
                    'label' => 'Pôle',
                ])
                ->add("status", EntityType::class, [
                    'class' => 'BPBundle:Status',
                    'choice_label' => 'translate.name',
                    'query_builder' => function (EntityRepository $er) {
                        return $er->myFindAll();
                    },
                    'label' => 'Statut',
                ])
                ->add('income', IzFloatType::class, [
                    'label' => 'Rémunération nette mensuelle',
                ])
                ->add('createdAt', DateTimeType::class, [
                    'label' => 'Date d\'embauche',
                    'widget' => 'single_text',
                    'format' => 'dd/MM/yyyy',
                    'attr' => [
                        'class' => 'validate datepicker datepicker_hiring'
                    ]
                ])
                ->add('finishedAt', DateTimeType::class, [
                    'required' => false,
                    'label' => 'Date de fin de contrat (Facultatif)',
                    'widget' => 'single_text',
                    'format' => 'dd/MM/yyyy',
                    'attr' => [
                        'class' => ' datepicker'
                    ]
                ])
                ->add('hours', null, [
                    'label' => 'Temps de travail annuel (%)',
                    'attr' => [
                        'class' => 'validate',
                        'min' => 0,
                        'max' => 100
                    ]
                ])
        ;
    }

    /**
     * @param OptionsResolver $resolver
     */
    public function configureOptions(OptionsResolver $resolver) {
        $resolver->setDefaults(array(
            'data_class' => 'BPBundle\Entity\Staff',
            'validation_groups' => ['staff']
        ));
    }

}
