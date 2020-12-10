<?php

namespace BPBundle\Form;

use Symfony\Component\Form\Extension\Core\Type\IntegerType;
use Symfony\Component\Form\AbstractType;
use BPBundle\Form\IzFloatType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class BfrType extends AbstractType {

    /**
     * @param FormBuilderInterface $builder
     * @param array $options
     */
    public function buildForm(FormBuilderInterface $builder, array $options) {
        $builder
                ->add('customer', IzFloatType::class, [
                    'label' => 'Délai de paiement théorique des clients (en jours)',
                ])
                ->add('provider', IzFloatType::class, [
                    'label' => 'Délai de paiement théorique des fournisseurs (en nbre de jours d\'achats)',
                ])
                ->add('acpteCustomer', IzFloatType::class, [
                    'label' => 'Estimation des avances et acomptes reçus des clients (en % des paiements)',
                ])
                ->add('acpteProvider', IzFloatType::class, [
                    'label' => 'Estimation des avances et acomptes à verser aux fournisseurs (en % des paiements)',
                ])
                ->add('stock', IzFloatType::class, [
                    'label' => 'Stock (en jours de marchandises vendues)',
                ])
        ;
    }

    /**
     * @param OptionsResolver $resolver
     */
    public function configureOptions(OptionsResolver $resolver) {
        $resolver->setDefaults(array(
            'data_class' => 'BPBundle\Entity\Bfr'
        ));
    }

}
