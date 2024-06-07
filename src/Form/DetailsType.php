<?php

namespace App\Form;

use App\Entity\Details;
use App\Entity\Event;
use App\Entity\Instrument;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class DetailsType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('min_payment')
            ->add('quantity')
            ->add('Event', EntityType::class, [
                'class' => Event::class,
                'choice_label' => 'id',
            ])
            ->add('requiredInstrument', EntityType::class, [
                'class' => Instrument::class,
                'choice_label' => 'id',
            ])
        ;
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => Details::class,
        ]);
    }
}
