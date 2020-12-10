<?php

namespace BackBundle\Form;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class PartnerType extends AbstractType {

    /**
     * @param FormBuilderInterface $builder
     * @param array $options
     */
    public function buildForm(FormBuilderInterface $builder, array $options) {
        $builder
                ->add('name', null, [
                    'label' => 'Nom',
                    'attr' => [
                        'class' => 'form-control'
                    ]
                ])
                ->add('websiteLink', null, [
                    'label' => 'Lien vers le site web',
                    'attr' => [
                        'class' => 'form-control'
                    ]
                ])
                ->add('description', null, [
                    'label' => 'Description',
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
    public function configureOptions(OptionsResolver $resolver) {
        $resolver->setDefaults(array(
            'data_class' => 'BackBundle\Entity\Partner'
        ));
    }

}
