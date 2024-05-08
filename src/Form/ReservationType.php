<?php

namespace App\Form;

use App\Entity\Book;
use App\Entity\Reservation;
use App\Entity\User;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class ReservationType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('reservation_date', null, [
                'widget' => 'single_text',
            ])
            ->add('return_date', null, [
                'widget' => 'single_text',
            ])
            ->add('book', EntityType::class, [
                'class' => Book::class,
                'choice_label' => 'name',
            ])
            ->add('student', EntityType::class, [
                'class' => User::class,
                'choice_label' => 'email',
            ])
            ->add('validator', EntityType::class, [
                'class' => User::class,
                'choice_label' => 'id',
            ])
        ;
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => Reservation::class,
        ]);
    }
}
