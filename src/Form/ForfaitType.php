<?php

namespace App\Form;

use App\Entity\Forfait;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\IntegerType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class ForfaitType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options) // formulaire de modification de frais forfaitiser
    {
        $builder
            ->add('quantite', IntegerType::class, [ //quantité du forfait
                'attr' => [
                    'type' => 'number',
                    'class' => '',
                    'min' => 0
                ]
            ])
            ->add('sauvegarder', SubmitType::class, [ //bouton de validation (envoi le formulaire)
                'attr' => [
                    'class' => '  save btn btn-sm btn-success far fa-edit',
                    'style' => 'float:right;font-size:14px;'
                ],
                'label'  => ' Valider',
            ]);
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'data_class' => Forfait::class,
        ]);
    }
}
