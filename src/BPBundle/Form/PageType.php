<?php

namespace BPBundle\Form;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class PageType extends AbstractType
{
    /**
     * {@inheritdoc}
     */
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('name', null, [
                'label' => 'Nom de l\'étape',
                'attr' => [
                    'class' => 'form-control validate'
                ]
            ])
            ->add('step', null, [
                'label' => 'Numéro de l\'étape',
                'attr' => [
                    'class' => 'form-control validate'
                ]
            ])
            ->add('info', null, [
                'label' => 'Informations de l\'étape',
                'attr' => [
                    'class' => 'form-control validate'
                ]
            ])        
        ;
    }
    
    /**
     * {@inheritdoc}
     */
    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults(array(
            'data_class' => 'BPBundle\Entity\Page'
        ));
    }

    /**
     * {@inheritdoc}
     */
    public function getBlockPrefix()
    {
        return 'bpbundle_page';
    }


}
