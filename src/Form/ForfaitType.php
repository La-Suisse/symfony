<?php

namespace App\Form;

use App\Entity\Forfait;
use App\Entity\TypeFrais;
use Doctrine\ORM\EntityRepository;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\NumberType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class ForfaitType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('quantite', NumberType::class, [
                'attr' => [
                    'class' => ''
                ]
            ])
            ->add('sauvegarder', SubmitType::class, [
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
