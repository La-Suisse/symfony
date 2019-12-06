<?php

namespace App\Form;

use App\Entity\Utilisateur;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\PasswordType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class UtilisateurType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options) //formulaire de connexion
    {
        $builder
            ->add('identifiant', null, [
                'label'  => 'Identifiant',
            ])
            ->add('mdp', PasswordType::class, [
                'label'  => 'Mot de passe',
            ])
            ->add('sauvegarder', SubmitType::class, [ //bouton de validation (envoi le formulaire)
                'attr' => [
                    'class' => 'sauvegarder btn btn-block'
                ],
                'label'  => 'Se Connecter',
            ]);
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'data_class' => Utilisateur::class,
        ]);
    }
}
