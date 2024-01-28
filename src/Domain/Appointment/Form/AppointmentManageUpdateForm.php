<?php

namespace App\Domain\Appointment\Form;

use App\Domain\Appointment\Dto\AppointmentManageUpdateData;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\DateType;
use Symfony\Component\Form\Extension\Core\Type\TimeType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class AppointmentManageUpdateForm extends AbstractType
{
    public function buildForm( FormBuilderInterface $builder, array $options ) : void
    {
        $builder
            ->add( 'date', DateType::class, [
                'label' => 'Date du rendez-vous',
                'widget' => 'single_text',
                'attr' => [
                    'class' => 'form-input-md flatpickr-date-input appointment-date-choice'
                ],
                'label_attr' => [
                    'class' => 'label'
                ],
            ] )
            ->add( 'time', TimeType::class, [
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
            'data_class' => AppointmentManageUpdateData::class,
        ] );
    }
}