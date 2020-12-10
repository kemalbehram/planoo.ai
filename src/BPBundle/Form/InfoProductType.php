<?php

namespace BPBundle\Form;

use Symfony\Component\Form\AbstractType;
use BPBundle\Form\IzFloatType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Form\FormEvents;
use Symfony\Component\Form\FormEvent;
use Symfony\Component\Form\FormInterface;
use Symfony\Component\Form\Extension\Core\Type\HiddenType;

class InfoProductType extends AbstractType {

    /**
     * {@inheritdoc}
     */
    public function buildForm(FormBuilderInterface $builder, array $options) {

        $formModifier = function (FormInterface $form, $isAdvanceMode = false) {
            if ($isAdvanceMode) {
                $form->add('nbVente', IzFloatType::class, [
                            'label' => 'Nombre estimé de produits/services vendus sur l’année',
                        ])
                        ->add('prixVente', IzFloatType::class, [
                            'label' => 'Prix unitaire (hors TVA) du produit/service vendu (€)',
                        ])
                        ->add('cout', IzFloatType::class, [
                            'label' => 'Coût de revient (Hors TVA) du produit/service (€)',
                ]);
            } else {
                $form->add('caExercice', IzFloatType::class, [
                            'label' => 'Chiffre d\'affaire prévisionnel par le produit/service sur l’année (€)',
                        ])
                        ->add('marge', IzFloatType::class, [
                            'label' => 'Taux de marge prévisionnel sur le produit (%)',
                ]);
            }
        };

        //set form type when loading new or edit form
        $builder->addEventListener(
                FormEvents::PRE_SET_DATA, function (FormEvent $event) use ($formModifier) {

            $isAdvanceMode = $event->getForm()->getConfig()->getOptions()['attr']['isAdvanceMode'];
            $formModifier($event->getForm(), $isAdvanceMode);
        }
        );

        $builder->addEventListener(
                FormEvents::PRE_SUBMIT, function (FormEvent $event) use ($formModifier) {

            $isAdvanceMode = $event->getForm()->getConfig()->getOptions()['attr']['isAdvanceMode'];

            $formModifier($event->getForm(), $isAdvanceMode);
        }
        );

        $builder->addEventListener(
                FormEvents::POST_SUBMIT, function (FormEvent $event) {

            $isAdvanceMode = $event->getForm()->getConfig()->getOptions()['attr']['isAdvanceMode'];

            $formData = $event->getForm()->getData();
            if ($isAdvanceMode) {
                $formData->setCAExercice($formData->getNbVente() * $formData->getPrixVente());
                $formData->setCoutExercice($formData->getNbVente() * $formData->getCout());
                $formData->setMarge(100 * ($formData->getCAExercice() - $formData->getCoutExercice()) / $formData->getCAExercice());
            } else {
                $formData->setNbVente(null);
                $formData->setPrixVente(null);
                $formData->setCout(null);
                $formData->setCoutExercice($formData->getCAExercice() * (100 - $formData->getMarge()) / 100);
            }
        }
        );

        $builder
                ->add('commission', IzFloatType::class, [
                    'label' => 'Commissions versées aux commerciaux (% du CA) - Si vous n’avez pas de commerciaux rémunérés en variable mettez le chiffre « 0 ».',
                    'attr' => [
                        'class' => 'validate',
                        'type' => 'number',
                        'min' => 0,
                        'max' => 100,
                        'step' => 0.01
                    ]
                ])
        ;
    }

    /**
     * {@inheritdoc}
     */
    public function configureOptions(OptionsResolver $resolver) {
        $resolver->setDefaults(array(
            'data_class' => 'BPBundle\Entity\InfoProduct',
            'validation_groups' => ['income']
        ));
    }

    /**
     * {@inheritdoc}
     */
    public function getBlockPrefix() {
        return 'bpbundle_infoproduct';
    }

}
