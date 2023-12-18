<?php

namespace App\Domain\Appointment\Form;

use App\Domain\Appointment\Dto\AppointmentData;
use App\Domain\Auth\Entity\User;
use App\Domain\Prestation\Entity\Prestation;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\DateType;
use Symfony\Component\Form\Extension\Core\Type\TimeType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class NewAppointmentForm extends AbstractType
{
    public function buildForm( FormBuilderInterface $builder, array $options ) : void
    {
        $builder
            ->add( 'client', EntityType::class, [
                'class' => User::class,
                'choice_label' => 'fullName',
                'label' => 'Client',
                'attr' => [
                    'class' => 'form-input-md'
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
                    'class' => 'form-input-md'
                ],
                'label_attr' => [
                    'class' => 'label'
                ],
            ] )
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
                ]
            );
    }

    public function configureOptions( OptionsResolver $resolver ) : void
    {
        $resolver->setDefaults( [
            'data_class' => AppointmentData::class,
        ] );
    }
}
