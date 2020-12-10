<?php

namespace PaymentBundle\Form;

use BPBundle\Form\AddressType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Validator\Constraints\Valid;

class RequestInvoiceType extends AbstractType {

    /**
     * {@inheritdoc}
     */
    public function buildForm(FormBuilderInterface $builder, array $options) {
        $builder
                ->add('company', null, [
                    'label' => 'Société',
                    'required' => true,
                    'attr' => [
                        'class' => "materialize-textarea validate",
                    ],
                ])
                ->add("address", AddressType::class, [
                    'label' => 'adresse de facturation',
                    'constraints' => array(new Valid()),
                    'required' => false,
                ])
            ;

    }

    /**
     * {@inheritdoc}
     */
    public function configureOptions(OptionsResolver $resolver) {
        $resolver->setDefaults(array(
            'data_class' => 'PaymentBundle\Entity\RequestInvoice'
        ));
    }

}
