<?php

namespace BPBundle\Form;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class CountryType extends AbstractType
{
    /**
     * @param FormBuilderInterface $builder
     * @param array $options
     */
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('code', null, [
                'label' => 'Code Pays',
                'attr' => [
                    'class' => 'validate'
                ]
            ])
            ->add('name', null, [
                'label' => 'Pays',
                'attr' => [
                    'class' => 'validate'
                ]
            ])
            ->add('file', null, [
                'label' => 'Drapeau'
            ])
        ;
    }
    
    /**
     * @param OptionsResolver $resolver
     */
    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults(array(
            'data_class' => 'BPBundle\Entity\Country'
        ));
    }
}
