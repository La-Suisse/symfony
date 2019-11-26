<?php

namespace App\Form;

use App\Entity\HorsForfait;
use DateTime;
use phpDocumentor\Reflection\Types\Float_;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\DateType;
use Symfony\Component\Form\Extension\Core\Type\NumberType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class HorsForfaitType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('date', DateType::class, [
                'attr' => [
                    'class' => ''
                ],
                'format' => 'ddMMyyyy',
                'widget' => 'choice'
            ])
            ->add('libelle', TextType::class, [
                'attr' => [
                    'class' => '',
                    'placeholder' => 'Libelle',
                    'style' => ''
                ]
            ])
            ->add('prix', NumberType::class, [
                'attr' => [
                    'class' => '',
                    'placeholder' => 'Prix',
                    'type' => 'number',
                ]
            ])
            ->add('sauvegarder', SubmitType::class, [
                'attr' => [
                    'class' => 'btn btn-sm btn-dark fas fa-plus-circle',
                    'style' => 'float:right;font-size:14px',
                ],
                'label'  => ' ',
            ]);
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'data_class' => HorsForfait::class,
        ]);
    }
}
