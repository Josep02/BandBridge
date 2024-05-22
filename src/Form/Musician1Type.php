<?php

namespace App\Form;

use App\Entity\Instrument;
use App\Entity\Login;
use App\Entity\Musician;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class Musician1Type extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('name')
            ->add('lastname')
            ->add('username')
            ->add('email')
            ->add('image')
            ->add('password')
            ->add('Instrument', EntityType::class, [
                'class' => Instrument::class,
                'choice_label' => 'id',
            ])
            ->add('login', EntityType::class, [
                'class' => Login::class,
                'choice_label' => 'id',
            ])
        ;
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => Musician::class,
        ]);
    }
}
