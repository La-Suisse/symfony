<?php

namespace App\Form;

use App\Entity\Forfait;
use App\Entity\TypeFrais;
use Doctrine\ORM\EntityRepository;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class ForfaitType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('quantite')
            ->add('monType', EntityType::class, array(
                'class' => TypeFrais::class,
                'query_builder' => function (EntityRepository $er) {
                    return $er->createQueryBuilder('type_frais')
                        ->orderBy('type_frais.libelle', 'ASC');
                },
                'choice_label' => 'libelle',
                'multiple' => true,
                'expanded' => true
            ));

    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'data_class' => Forfait::class,
        ]);
    }
}
