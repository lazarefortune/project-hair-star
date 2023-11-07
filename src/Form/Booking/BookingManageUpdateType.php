<?php

namespace App\Form\Booking;

use App\Dto\Admin\Booking\BookingManageUpdateDto;
use App\Entity\Prestation;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\DateType;
use Symfony\Component\Form\Extension\Core\Type\TimeType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class BookingManageUpdateType extends AbstractType
{
    public function buildForm( FormBuilderInterface $builder, array $options ) : void
    {
        $builder
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
            ] );
    }

    public function configureOptions( OptionsResolver $resolver ) : void
    {
        $resolver->setDefaults( [
            'data_class' => BookingManageUpdateDto::class,
        ] );
    }
}