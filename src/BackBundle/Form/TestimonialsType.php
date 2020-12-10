<?php

namespace BackBundle\Form;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\DateTimeType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class TestimonialsType extends AbstractType
{
    /**
     * @param FormBuilderInterface $builder
     * @param array $options
     */
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('content', null, [
                'label' => ' Contenu',
                'attr' => [
                    'class' => 'form-control'
                ]
            ])
            ->add('author', null, [
                'label' => 'Auteur',
                'attr' => [
                    'class' => 'form-control'
                ]
            ])
            ->add('createdAt', DateTimeType::class, [
                'widget' => 'single_text',
                'format' => 'dd-MM-yyyy',
                'attr' => [
                    'class' => 'datepicker form-control',
                    'data-provide' => 'datepicker',
                    'data-date-format' => 'dd-mm-yyyy'
                ],
                'required' => false
            ])
            ->add('note', null, [
                'label' => 'Note',
                'attr' => [
                    'class' => 'form-control'
                ]
            ])
            ->add('file', null, [
                'label' => 'Image',
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
            'data_class' => 'BackBundle\Entity\Testimonials'
        ));
    }
}
