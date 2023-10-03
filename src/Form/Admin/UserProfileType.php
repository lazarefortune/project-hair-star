<?php

namespace App\Form\Admin;

use App\Dto\Admin\Profile\AdminProfileUpdateDto;
use App\Entity\User;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\DateType;
use Symfony\Component\Form\Extension\Core\Type\EmailType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Vich\UploaderBundle\Form\Type\VichFileType;

class UserProfileType extends AbstractType
{
    public function buildForm( FormBuilderInterface $builder, array $options ) : void
    {
        $builder
            ->add( 'fullname', TextType::class, [
                'label' => 'Nom complet',
                'attr' => [
                    'class' => 'form-input-md'
                ],
                'label_attr' => [
                    'class' => 'label'
                ]
            ] )
            ->add( 'phone', TextType::class, [
                'label' => 'Numéro de téléphone',
                'attr' => [
                    'class' => 'form-input-md'
                ],
                'label_attr' => [
                    'class' => 'label'
                ]
            ] )
            ->add( 'email', EmailType::class, [
                'label' => 'Adresse email',
                'attr' => [
                    'class' => 'form-input-md'
                ],
                'label_attr' => [
                    'class' => 'label'
                ]
            ] )
            ->add( 'dateOfBirthday', DateType::class, [
                'label' => 'Date de naissance',
                'widget' => 'single_text',
                'html5' => false,
                'attr' => [
                    'class' => 'flatpickr-date-birthday form-input-md',
                ],
                'label_attr' => [
                    'class' => 'label'
                ]
            ] );
    }

    public function configureOptions( OptionsResolver $resolver ) : void
    {
        $resolver->setDefaults( [
            'data_class' => AdminProfileUpdateDto::class,
        ] );
    }
}