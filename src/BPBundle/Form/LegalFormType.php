<?php

namespace BPBundle\Form;

use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class LegalFormType extends AbstractType
{
    /**
     * @param FormBuilderInterface $builder
     * @param array $options
     */
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('name', null, [
                'label' => 'Libellé',
                'attr' => [
                    'class' => 'form-control'
                ]
            ])
            ->add('code', null, [
                'label' => 'Code',
                'attr' => [
                    'class' => 'form-control'
                ]
            ])
            -> add("countries", EntityType::class, [
                'class' => 'BPBundle:Country',
                'choice_label' => 'name',
                'multiple' => true,
                'expanded' => true,
            ])
        ;
    }
    
    /**
     * @param OptionsResolver $resolver
     */
    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults(array(
            'data_class' => 'BPBundle\Entity\LegalForm'
        ));
    }
}
