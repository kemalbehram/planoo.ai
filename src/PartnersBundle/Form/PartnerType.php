<?php

namespace PartnersBundle\Form;

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
                ->add('nom', null, [
                    'label' => 'Nom',
                    'attr' => [
                        'class' => 'form-control'
                    ]
                ])->add('idWordpressAffiliate', null, [
                    'label' => 'Identifiant affiliation Wordpress ',
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
            'data_class' => 'PartnersBundle\Entity\Partner'
        ));
    }

}
