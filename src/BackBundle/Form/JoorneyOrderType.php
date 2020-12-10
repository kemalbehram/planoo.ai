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
use BackBundle\Form\JoorneyOrderUserType;
use Symfony\Component\Validator\Constraints\Valid;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;

class JoorneyOrderType extends AbstractType {

    /**
     * {@inheritdoc}
     */
    public function buildForm(FormBuilderInterface $builder, array $options) {
        $builder
                ->add('catalog', EntityType::class, [
                    'label' => 'Service demandé',
                    'class' => "PromotionBundle:Catalog",
                    'disabled' => true,
                    'choice_label' => 'name'
                ])
                ->add("user", JoorneyOrderUserType::class, [
                    'label' => false,
                    'constraints' => array(new Valid())
                ])
                ->add('preferedTime', ChoiceType::class, [
                    'label' => 'Sur quel créneau préférez-vous être rappelé ?',
                    'required' => true,
                    'choices' => [
                        'Entre 10h00 et 11h00' => '10:00AM to 11:00AM',
                        'Entre 11h00 et 12h00' => '11:00AM to 12:00AM',
                        'Entre 12h00 et 13h00' => '12:00AM to 01:00PM',
                        'Entre 13h00 et 14h00' => '01:00PM to 02:00PM',
                        'Entre 14h00 et 15h00' => '02:00PM to 03:00PM',
                        'Entre 15h00 et 16h00' => '03:00PM to 04:00PM',
                        'Entre 16h00 et 17h00' => '04:00PM to 05:00PM',
                        'Entre 17h00 et 18h00' => '05:00PM to 06:00PM',
                        'Entre 18h00 et 18h30' => '06:00PM to 06:30PM',
                    ], 'attr' => [
                        'class' => 'validate',
                    ],
                ])
                ->add('activityField', null, [
                    'label' => 'Domaine d\'activité',
                    'required' => true,
                    'attr' => [
                        'class' => "materialize-textarea validate",
                    ],
                ])
                ->add('activityDescription', TextareaType::class, [
                    'label' => 'Brève description de votre projet (Optionnelle)',
                    'required' => false,
                    'attr' => [
                        'class' => "materialize-textarea validate",
                    ],
        ]);


        if (isset($options['attr']['upload']) && $options['attr']['upload']) {
            $builder->add('file', null, [
                'label' => 'Votre document (20Mo max)',
                'constraints' => array(new Valid()),
                'attr' => [
                    'accept' => 'application/vnd.openxmlformats-officedocument.presentationml.presentation, application/vnd.ms-powerpoint,application/msword,application/vnd.openxmlformats-officedocument.wordprocessingml.document,application/pdf,application/vnd.oasis.opendocument.presentation,application/vnd.oasis.opendocument.text'
                ]
            ]);
        }

        if (isset($options['attr']['funding']) && $options['attr']['funding']) {
            $builder->add('funding', ChoiceType::class, [
                'label' => 'Financement recherché',
                'constraints' => array(new Valid()),
                'choices' => [
                    'Moins de 100 K€' => 'Moins de 100 K€',
                    'Entre 100 et 300 K€' => ' Entre 100 et 300 K€',
                    'Entre 301 et 700 K€' => 'Entre 301 et 700 K€',
                    'Entre 701 et 1 000 K€' => 'Entre 701 et 1 000 K€',
                    'Entre 1 001 K€ et 5 000 K€' => 'Entre 1 001 K€ et 5 000 K€',
                    'Plus de 5 001 K€' => 'Plus de 5 001 K€',
                ],
            ]);
        }
    }

    /**
     * {@inheritdoc}
     */
    public function configureOptions(OptionsResolver $resolver) {
        $resolver->setDefaults(array(
            'data_class' => 'BackBundle\Entity\JoorneyOrder'
        ));
    }

}
