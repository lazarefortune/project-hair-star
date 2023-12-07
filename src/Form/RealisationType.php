<?php

namespace App\Form;

use App\Entity\Realisation;
use App\Form\Type\SwitchboxType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\DateType;
use Symfony\Component\Form\Extension\Core\Type\FileType;
use Symfony\Component\Form\Extension\Core\Type\MoneyType;
use Symfony\Component\Form\Extension\Core\Type\TimeType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Validator\Constraints\File;

class RealisationType extends AbstractType
{
    public function buildForm( FormBuilderInterface $builder, array $options ) : void
    {
        $builder
            ->add( 'isPublic', SwitchboxType::class, [
                'input_label' => 'En ligne ?',
                'label_on' => 'Oui',
                'label_off' => 'Non',
            ] )
            ->add( 'tarif', MoneyType::class, [
                'attr' => [
                    'class' => 'form-input-md',
                ],
                'label' => 'Tarif',
                'currency' => 'EUR',
                'required' => true,
                'label_attr' => [
                    'class' => 'label',
                ],
            ] )
            ->add( 'isTarifPublic', SwitchboxType::class, [
                'label_on' => 'Oui',
                'label_off' => 'Non',
                'input_label' => 'Prix public ?',
            ] )
            ->add( 'dateRealisation', DateType::class, [
                'required' => true,
                'widget' => 'single_text',
                'html5' => false,
                'label' => 'Date de la réalisation',
                'label_attr' => [
                    'class' => 'label',
                ],
                'attr' => [
                    'class' => 'flatpickr-date-realisation form-input-md',
                    'data-input' => 'true'
                ],
            ] )
            ->add( 'duration', TimeType::class, [
                'label' => 'Durée de la réalisation',
                'required' => true,
                'widget' => 'single_text',
                'attr' => [
                    'class' => 'flatpickr-time-input form-input-md',
                ],
                'label_attr' => [
                    'class' => 'label',
                ],
            ] )
            ->add( 'images', FileType::class, [
                'label' => 'Ajoutez des images',
                'mapped' => false,
                'multiple' => true,
                'required' => false,
                'attr' => [
                    'class' => 'inputfile inputfile-6',
                    'placeholder' => 'Images',
                    'data-multiple-caption' => '{count} fichiers sélectionnés',
                ],
            ] );
    }


    public function configureOptions( OptionsResolver $resolver ) : void
    {
        $resolver->setDefaults( [
            'data_class' => Realisation::class,
        ] );
    }
}
