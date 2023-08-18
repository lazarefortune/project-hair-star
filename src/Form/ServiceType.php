<?php

namespace App\Form;

use App\DataTransformer\MinutesToTimeTransformer;
use App\Entity\CategoryService;
use App\Entity\Service;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\Extension\Core\Type\DateType;
use Symfony\Component\Form\Extension\Core\Type\IntegerType;
use Symfony\Component\Form\Extension\Core\Type\MoneyType;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\Extension\Core\Type\TimeType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class ServiceType extends AbstractType
{

    public function __construct( private readonly MinutesToTimeTransformer $transformer )
    {
    }

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
            ->add( 'price', MoneyType::class, [
                'label' => 'Prix du service',
                'currency' => 'EUR',
                'required' => true,
                'attr' => [
                    'class' => 'form-input-md',
                ],
                'label_attr' => [
                    'class' => 'label',
                ],
            ] )
            ->add( 'duration', TimeType::class, [
                'label' => 'Durée du service',
                'widget' => 'single_text',
                'attr' => [
                    'class' => 'form-input-md flatpickr-time-input',
                ],
                'label_attr' => [
                    'class' => 'label',
                ],
            ] )
            ->add( 'startDate', DateType::class, [
                'label' => 'Date de début du service',
                'widget' => 'single_text',
                'attr' => [
                    'class' => 'form-input-md flatpickr-date-after-today-default-today',
                ],
                'label_attr' => [
                    'class' => 'label',
                ],
            ] )
            ->add( 'endDate', DateType::class, [
                'label' => 'Date de fin du service',
                'widget' => 'single_text',
                'attr' => [
                    'class' => 'form-input-md flatpickr-date-after-today-default-after-month',
                ],
                'label_attr' => [
                    'class' => 'label',
                ],
            ] )
            ->add( 'startTime', TimeType::class, [
                'label' => 'Heure de début du service',
                'widget' => 'single_text',
                'attr' => [
                    'class' => 'form-input-md flatpickr-time-wrap',
                ],
                'label_attr' => [
                    'class' => 'label',
                ],
            ] )
            ->add( 'endTime', TimeType::class, [
                'label' => 'Heure de fin du service',
                'widget' => 'single_text',
                'attr' => [
                    'class' => 'form-input-md flatpickr-time-wrap',
                ],
                'label_attr' => [
                    'class' => 'label',
                ],
            ] )
            ->add( 'categories', EntityType::class, [
                'class' => CategoryService::class,
                'choice_label' => 'name',
                'multiple' => true,
                'label' => 'Catégories du service',
                'attr' => [
                    'class' => 'form-input-md select2',
                ],
                'label_attr' => [
                    'class' => 'label',
                ],
            ] )
            ->add( 'avalaibleSpacePerService', IntegerType::class, [
                'label' => 'Nombre de places disponibles',
                'attr' => [
                    'class' => 'form-input-md',
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
                    'class' => 'form-input-md',
                ],
                'label_attr' => [
                    'class' => 'label',
                ],
            ] );

        $builder->get( 'bufferTime' )->addModelTransformer( $this->transformer );
    }

    public function configureOptions( OptionsResolver $resolver ) : void
    {
        $resolver->setDefaults( [
            'data_class' => Service::class,
        ] );
    }
}
