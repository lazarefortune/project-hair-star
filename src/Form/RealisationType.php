<?php

namespace App\Form;

use App\Entity\Realisation;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\CheckboxType;
use Symfony\Component\Form\Extension\Core\Type\DateType;
use Symfony\Component\Form\Extension\Core\Type\FileType;
use Symfony\Component\Form\Extension\Core\Type\NumberType;
use Symfony\Component\Form\Extension\Core\Type\TimeType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Validator\Constraints\File;

class RealisationType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('isPublic', CheckboxType::class, [
                'label' => 'Rendre la réalisation publique',
                'required' => false,
                'attr' => [
                    'class' => 'form-check-input',
                ],
            ])
            ->add('tarif', NumberType::class, [
                'required' => false,
                'attr' => [
                    'class' => 'form-control form-control-tarif',
                ],
                'scale' => 2,
            ])
            ->add('isTarifPublic', CheckboxType::class, [
                'label' => 'Rendre le tarif publique',
                'required' => false,
                'attr' => [
                    'class' => 'form-check-input',
                ],
            ])
            ->add('dateRealisation', DateType::class, [
                'required' => false,
                'widget' => 'single_text',
                'label' => 'Date de la réalisation',
                'attr' => [
                    'class' => 'form-control',
                ],
            ])
            ->add('heureDebut', TimeType::class, [
                'label' => 'Heure de début',
                'required' => false,
                'widget' => 'single_text',
                'attr' => [
                    'class' => 'form-control',
                ],
            ])
            ->add('heureFin', TimeType::class, [
                'label' => 'Heure de fin',
                'required' => false,
                'widget' => 'single_text',
                'attr' => [
                    'class' => 'form-control',
                ],
            ])
            ->add('images', FileType::class, [
                'label' => 'Ajoutez des images',
                'mapped' => false,
                'multiple' => true,
                'required' => false,
                'attr' => [
                    'class' => 'form-control form-control-file',
                    'placeholder' => 'Images',
                ],
            ])
        ;
    }



    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => Realisation::class,
        ]);
    }
}
