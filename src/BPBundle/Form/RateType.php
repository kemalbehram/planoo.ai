<?php

namespace BPBundle\Form;

use Doctrine\ORM\EntityRepository;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class RateType extends AbstractType
{
    /**
     * @param FormBuilderInterface $builder
     * @param array $options
     */
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('type', EntityType::class, [
                'class' => 'BPBundle:RateLabel',
                'choice_label' => 'name',
                'query_builder' => function (EntityRepository $er) {
                    return $er->createQueryBuilder('rl')
                        ->orderBy('rl.name', 'ASC');
                },
                'attr' => [
                    'class' => 'form-control'
                ]
            ])
            ->add('value', null, [
                'label' => 'Valeur',
                'attr' => [
                    'class' => 'form-control'
                ]
            ])
            ->add('base', null, [
                'label' => 'Base',
                'attr' => [
                    'class' => 'form-control'
                ]
            ])
            ->add('baseMin', null, [
                'label' => 'Base minimum',
                'attr' => [
                    'class' => 'form-control'
                ]
            ])
            ->add('baseMax', null, [
                'label' => 'Base maximum',
                'attr' => [
                    'class' => 'form-control'
                ]
            ])
            -> add("country", EntityType::class, [
                'class' => 'BPBundle:Country',
                'choice_label' => 'name',
                'query_builder' => function (EntityRepository $er) {
                    return $er->createQueryBuilder('c')
                        ->orderBy('c.name', 'ASC');
                },
                'label' => 'Locale',
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
            'data_class' => 'BPBundle\Entity\Rate'
        ));
    }
}
