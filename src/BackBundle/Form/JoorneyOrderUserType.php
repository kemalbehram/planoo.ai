<?php

namespace BackBundle\Form;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use PromotionBundle\Repository\CatalogRepository;
use PromotionBundle\Entity\Catalog;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Doctrine\ORM\EntityRepository;
use Symfony\Component\Form\Extension\Core\Type\TelType;

class JoorneyOrderUserType extends AbstractType {

    /**
     * {@inheritdoc}
     */
    public function buildForm(FormBuilderInterface $builder, array $options) {
        $builder
                ->add('email', null, [
                    'label' => 'Votre email',
                    'disabled' => true,
                ])
                ->add('firstname', null, [
                    'label' => 'Votre prénom',
                    'attr' => [
                        'class' => 'validate',
                    ],
                ])
                ->add('lastname', null, [ 
                    'label' => 'Votre nom',
                    'attr' => [
                        'class' => 'validate',
                    ],
                ])
                ->add('phoneNumber', TelType::class, [
                    'label' => 'Votre numéro de téléphone',
                    'attr' => [
                        'pattern' => '[0-9]{10,14}',
                        'class' => 'validate',
                    ],
                ])
        ;
    }

    /**
     * {@inheritdoc}
     */
    public function configureOptions(OptionsResolver $resolver) {
        $resolver->setDefaults(array(
            'data_class' => 'UserBundle\Entity\User'
        ));
    }

}
