<?php

namespace BPBundle\Form;

use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Validator\Constraints\Valid;
use BPBundle\Repository\LegalFormRepository;
use BPBundle\Repository\LocationRepository;
use BPBundle\Repository\ActivityRepository;

class InformationType extends AbstractType {

    /**
     * @param FormBuilderInterface $builder
     * @param array $options
     */
    public function buildForm(FormBuilderInterface $builder, array $options) {

        $builder
                ->add('nameCorporate', null, [
                    'label' => 'Dénomination sociale',
                    'attr' => [
                        'class' => 'validate'
                    ]
                ])
                ->add("legalForm", EntityType::class, [
                    'label' => 'Forme juridique',
                    'class' => 'BPBundle:LegalForm',
                    'choice_label' => 'name',
                    'query_builder' => function (LegalFormRepository $er) {
                        return $er->createQueryBuilder('ent')->where('ent.id <>0');
                    },
                    'attr' => [
                        'class' => 'validate'
                    ]
                ])->add('ir', ChoiceType::class, [
                    'label' => "Régime d'imposition",
                    'choices' => [
                        'IS' => 0,
                        'IR' => 1
                    ]
                ])
                ->add('firstnameOwner', null, [
                    'label' => 'Prénom',
                    'attr' => [
                        'class' => 'validate'
                    ]
                ])
                ->add('lastnameOwner', null, [
                    'label' => 'Nom',
                    'attr' => [
                        'class' => 'validate'
                    ]
                ])
                ->add('file', null, [
                    'label' => false,
                    'constraints' => array(new Valid()),
                    'attr' => [
                        'accept' => 'image/*'
                    ]
                ])
                ->add('franchise', ChoiceType::class, [
                    'label' => "Êtes-vous franchisé",
                    'choices' => [
                        'Non' => 0,
                        'Oui' => 1
                    ]
                ])
                ->add('nameFranchise', null, [
                    'label' => 'Nom de la franchise (Facultatif)',
                    'attr' => [
                        'class' => 'validate'
                    ]
                ])
                ->add("location", EntityType::class, [
                    'label' => 'Emplacement',
                    'class' => 'BPBundle:Location',
                    'query_builder' => function (LocationRepository $er) {
                        return $er->createQueryBuilder('ent')->where('ent.id <>0');
                    },
                    'choice_label' => 'name',
                    'attr' => [
                        'class' => 'validate'
                    ]
                ])
                ->add("address", AddressType::class, [
                    'label' => false,
                    'constraints' => array(new Valid())
                ])
                ->add("activity", EntityType::class, [
                    'label' => 'Activité',
                    'class' => 'BPBundle:Activity',
                    'query_builder' => function (ActivityRepository $er) {
                        return $er->createQueryBuilder('act')->orderBy('act.category', 'asc')->addOrderBy('act.name', 'asc');
                    },
                    'choice_label' => 'label',
                    'attr' => [
                        'class' => 'validate'
                    ]
                ])
                ->add('activityDetail', null, [
                    'label' => 'Précision sur l\'activité',
                    'required' => true,
                    'attr' => [
                        'class' => 'validate'
                    ]
                ])
        ;
    }

    /**
     * @param OptionsResolver $resolver
     */
    public function configureOptions(OptionsResolver $resolver) {
        $resolver->setDefaults([
            'data_class' => 'BPBundle\Entity\Information',
            'validation_groups' => ['information'],
            'entityManager' => null
        ]);
    }

}
