<?php

namespace App\Form;

use App\Entity\Musician;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\FileType;
use Symfony\Component\Form\Extension\Core\Type\PasswordType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class MusicianType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('name')
            ->add('lastname')
            ->add('email')
            ->add('avatar', FileType::class, [
                'label' => 'Avatar',
                'required' => false,
                'mapped' => false,
            ])
            ->add('username', TextType::class, [
                'mapped' => false,
                'required' => true,
            ])
            ->add('password', PasswordType::class, [
                'mapped' => false,
                'required' => true,
            ]);
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => Musician::class,
        ]);
    }
}
