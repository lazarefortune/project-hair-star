<?php

namespace App\Form;

use App\Entity\CategoryService;
use App\Entity\Service;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\Extension\Core\Type\DateType;
use Symfony\Component\Form\Extension\Core\Type\MoneyType;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\Extension\Core\Type\TimeType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class ServiceType extends AbstractType
{
    public function buildForm( FormBuilderInterface $builder, array $options ) : void
    {
        $builder
            ->add( 'name', TextType::class, [
                'label' => 'Nom du service',
                'attr' => [
                    'class' => '',
                ],
                'label_attr' => [
                    'class' => 'label',
                ],
            ] )
            ->add( 'description', TextareaType::class, [
                'label' => 'Description du service',
                'attr' => [
                    'class' => '',
                ],
                'required' => false,
                'label_attr' => [
                    'class' => 'label',
                ],
            ] )
            ->add( 'price', TextType::class, [
                'label' => 'Prix du service',
                'attr' => [
                    'class' => '',
                ],
                'label_attr' => [
                    'class' => 'label',
                ],
            ] )
            ->add( 'duration', TimeType::class, [
                'label' => 'Durée du service',
                'widget' => 'single_text',
                'attr' => [
                    'class' => 'flatpickr-time-input',
                ],
                'label_attr' => [
                    'class' => 'label',
                ],
            ] )
            ->add( 'startDate', DateType::class, [
                'label' => 'Date de début du service',
                'widget' => 'single_text',
                'attr' => [
                    'class' => '',
                ],
                'label_attr' => [
                    'class' => 'label',
                ],
            ] )
            ->add( 'endDate', DateType::class, [
                'label' => 'Date de fin du service',
                'widget' => 'single_text',
                'attr' => [
                    'class' => '',
                ],
                'label_attr' => [
                    'class' => 'label',
                ],
            ] )
            ->add( 'startTime', ChoiceType::class, [
                'label' => 'Heure de début du service',
                'data' => '09:00',
                'choices' => [
                    '08:00' => '08:00',
                    '08:30' => '08:30',
                    '09:00' => '09:00',
                    '09:30' => '09:30',
                    '10:00' => '10:00',
                    '10:30' => '10:30',
                    '11:00' => '11:00',
                    '11:30' => '11:30',
                    '12:00' => '12:00',
                    '12:30' => '12:30',
                    '13:00' => '13:00',
                    '13:30' => '13:30',
                    '14:00' => '14:00',
                    '14:30' => '14:30',
                    '15:00' => '15:00',
                    '15:30' => '15:30',
                    '16:00' => '16:00',
                    '16:30' => '16:30',
                    '17:00' => '17:00',
                    '17:30' => '17:30',
                    '18:00' => '18:00',
                    '18:30' => '18:30',
                    '19:00' => '19:00',
                    '19:30' => '19:30',
                    '20:00' => '20:00',
                    '20:30' => '20:30',
                    '21:00' => '21:00',
                    '21:30' => '21:30',
                    '22:00' => '22:00',
                    '22:30' => '22:30',
                    '23:00' => '23:00',
                    '23:30' => '23:30'
                ],
                'attr' => [
                    'class' => 'form-input-md',
                ],
                'label_attr' => [
                    'class' => 'label',
                ],
            ] )
            ->add( 'endTime', ChoiceType::class, [
                'label' => 'Heure de début du service',
                'data' => '18:00',
                'choices' => [
                    '08:00' => '08:00',
                    '08:30' => '08:30',
                    '09:00' => '09:00',
                    '09:30' => '09:30',
                    '10:00' => '10:00',
                    '10:30' => '10:30',
                    '11:00' => '11:00',
                    '11:30' => '11:30',
                    '12:00' => '12:00',
                    '12:30' => '12:30',
                    '13:00' => '13:00',
                    '13:30' => '13:30',
                    '14:00' => '14:00',
                    '14:30' => '14:30',
                    '15:00' => '15:00',
                    '15:30' => '15:30',
                    '16:00' => '16:00',
                    '16:30' => '16:30',
                    '17:00' => '17:00',
                    '17:30' => '17:30',
                    '18:00' => '18:00',
                    '18:30' => '18:30',
                    '19:00' => '19:00',
                    '19:30' => '19:30',
                    '20:00' => '20:00',
                    '20:30' => '20:30',
                    '21:00' => '21:00',
                    '21:30' => '21:30',
                    '22:00' => '22:00',
                    '22:30' => '22:30',
                    '23:00' => '23:00',
                    '23:30' => '23:30'
                ],
                'attr' => [
                    'class' => 'form-input-md',
                ],
                'label_attr' => [
                    'class' => 'label',
                ],
            ] )
//            ->add( 'categories', EntityType::class, [
//                'class' => CategoryService::class,
//                'choice_label' => 'name',
//                'multiple' => true,
//                'label' => 'Catégories du service',
//                'attr' => [
//                    'class' => 'select2 form-input-md',
//                ],
//                'label_attr' => [
//                    'class' => 'label',
//                ],
//            ] )
            ->add( 'avalaibleSpacePerService', TextType::class, [
                'label' => 'Nombre de places disponibles',
                'attr' => [
                    'class' => '',
                ],
                'label_attr' => [
                    'class' => 'label',
                ],
            ] )
            ->add( 'bufferTime', ChoiceType::class, [
                'label' => 'Temps de battement',
                'choices' => [
                    '5 minutes' => 5,
                    '10 minutes' => 10,
                    '15 minutes' => 15,
                    '20 minutes' => 20,
                    '25 minutes' => 25,
                    '30 minutes' => 30,
                    '35 minutes' => 35,
                    '40 minutes' => 40,
                    '45 minutes' => 45,
                    '50 minutes' => 50,
                    '55 minutes' => 55,
                    '60 minutes' => 60,
                ],
                'attr' => [
                    'class' => ' form-input-md',
                ],
                'label_attr' => [
                    'class' => 'label',
                ],
            ] );
    }

    public function configureOptions( OptionsResolver $resolver ) : void
    {
        $resolver->setDefaults( [
            'data_class' => Service::class,
        ] );
    }
}
