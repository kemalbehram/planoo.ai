<?php

namespace UserBundle\Form;

use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Doctrine\ORM\EntityRepository;

class SocialChargeType extends AbstractType
{
    /**
     * @param FormBuilderInterface $builder
     * @param array $options
     */
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            -> add("country", EntityType::class, [
                'class' => 'BPBundle:Country',
                'choice_label' => 'name',
                'query_builder' => function (EntityRepository $er) {
                    return $er->createQueryBuilder('c')
                        ->orderBy('c.name', 'ASC');
                },
                'attr' => [
                    'class' => 'form-control'
                ]
            ])
            -> add("status", EntityType::class, [
                'class' => 'BPBundle:Status',
                'choice_label' => 'translate.name',
                'query_builder' => function (EntityRepository $er) {
                    return $er->myFindAll();
                },
                'label' => 'Status',
                'attr' => [
                    'class' => 'form-control'
                ]
            ])
            ->add('chargeSalariale', null, [
                'label' => "taux de charges salariales",
                'attr' => [
                    'class' => 'form-control'
                ]
            ])
            ->add('chargePatronale', null, [
                'label' => "taux de charges patronales",
                'attr' => [
                    'class' => 'form-control'
                ]
            ])
            ->add('cice', null, [
                'label' => "Eligibilité CICE",
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
            'data_class' => 'UserBundle\Entity\SocialCharge'
        ));
    }
}
