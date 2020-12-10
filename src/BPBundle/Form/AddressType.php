<?php

namespace BPBundle\Form;

use Doctrine\ORM\EntityRepository;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\CollectionType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class AddressType extends AbstractType {

    /**
     * @param FormBuilderInterface $builder
     * @param array $options
     */
    public function buildForm(FormBuilderInterface $builder, array $options) {
        $builder
                ->add('street', null, [
                    'label' => 'Rue',
                    'attr' => [
                        'class' => 'validate'
                    ],
                ])
                ->add('codePostal', null, [
                    'label' => 'Code postal',
                    'attr' => [
                        'class' => 'validate'
                    ]
                ])
                ->add('city', null, [
                    'label' => 'Ville',
                    'attr' => [
                        'class' => 'validate'
                    ]
                ])
                ->add("country", EntityType::class, [
                    'label' => 'Pays',
                    'class' => 'BPBundle:Country',
                    'choice_label' => 'name',
                    'query_builder' => function (EntityRepository $er) {
                        return $er->createQueryBuilder('c')
                                ->addSelect('(CASE WHEN c.name=\'France\' then 1 ELSE 2 END) AS HIDDEN tmp') // HIDDEN is important
                                ->addOrderBy('tmp', 'desc')->addOrderBy('c.name', 'desc');
                    }
                ])
        ;
    }

    /**
     * @param OptionsResolver $resolver
     */
    public function configureOptions(OptionsResolver $resolver) {
        $resolver->setDefaults(array(
            'data_class' => 'BPBundle\Entity\Address'
        ));
    }

}
