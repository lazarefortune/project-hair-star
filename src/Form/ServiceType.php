<?php

namespace App\Form;

use App\Entity\CategoryService;
use App\Entity\Service;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\AbstractType;
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
            ->add( 'startTime', TimeType::class, [
                'label' => 'Heure de début du service',
                'widget' => 'single_text',
                'attr' => [
                    'class' => 'flatpickr-time-input',
                ],
                'label_attr' => [
                    'class' => 'label',
                ],
            ] )
            ->add( 'endTime', TimeType::class, [
                'label' => 'Heure de fin du service',
                'widget' => 'single_text',
                'attr' => [
                    'class' => 'flatpickr-time-input',
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
            ->add( 'bufferTime', TimeType::class, [
                'label' => 'Temps d\'intervalle entre deux services',
                'widget' => 'single_text',
                'attr' => [
                    'class' => 'flatpickr-time-input',
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
