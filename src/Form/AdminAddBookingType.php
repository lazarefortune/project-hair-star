<?php

namespace App\Form;

use App\Entity\Booking;
use App\Entity\User;
use App\Entity\Prestation;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\DateTimeType;
use Symfony\Component\Form\Extension\Core\Type\DateType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\Extension\Core\Type\TimeType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class AdminAddBookingType extends AbstractType
{
    public function buildForm( FormBuilderInterface $builder, array $options ) : void
    {
        $builder
            ->add( 'client', EntityType::class, [
                'class' => User::class,
                'choice_label' => 'fullName',
                'label' => 'Client',
                'attr' => [
                    'class' => 'select'
                ],
                'query_builder' => function ( \Doctrine\ORM\EntityRepository $er ) {
                    return $er->createQueryBuilder( 'u' )
                        ->where( 'u.roles LIKE :role' )
                        ->setParameter( 'role', '%"' . 'ROLE_CLIENT' . '"%' );
                },
                'label_attr' => [
                    'class' => 'label'
                ],
            ] )
            ->add( 'prestation', EntityType::class, [
                'class' => Prestation::class,
                'choice_label' => 'name',
                'label' => 'Prestation',
                'attr' => [
                    'class' => 'select'
                ],
                'label_attr' => [
                    'class' => 'label'
                ],
            ] )
            ->add( 'bookingDate', DateType::class, [
                'label' => 'Date du rendez-vous',
                'widget' => 'single_text',
                'attr' => [
                    'class' => 'form-input-md flatpickr-date-input booking-date-choice'
                ],
                'label_attr' => [
                    'class' => 'label'
                ],
            ] )
            ->add( 'bookingTime', TimeType::class, [
                    'label' => 'Heure du rendez-vous',
                    'widget' => 'single_text',
                    'attr' => [
                        'class' => 'form-input-md flatpickr-time-input'
                    ],
                    'label_attr' => [
                        'class' => 'label'
                    ],
                ]
            );
    }

    public function configureOptions( OptionsResolver $resolver ) : void
    {
        $resolver->setDefaults( [
            'data_class' => Booking::class,
        ] );
    }
}
