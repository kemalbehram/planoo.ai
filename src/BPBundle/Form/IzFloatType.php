<?php

namespace BPBundle\Form;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\NumberType;
use Symfony\Component\OptionsResolver\OptionsResolver;

class IzFloatType extends AbstractType {

    public function getParent() {
        return NumberType::class;
    }

    public function getName() {
        return 'iz_float';
    }

    public function getBlockPrefix() {
        return 'iz_float';
    }

    public function configureOptions(OptionsResolver $resolver) {
        $resolver->setDefaults(array(
            'attr' => [
                'class' => 'form-control validate',
                'pattern' => '\d+([\.|,]\d{1,2})?'
            ]
        ));
    }

}
