<?php

namespace App\Form;

use App\Entity\User;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\DateType;
use Symfony\Component\Form\Extension\Core\Type\FileType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Vich\UploaderBundle\Form\Type\VichFileType;

class UserType extends AbstractType
{
    public function buildForm( FormBuilderInterface $builder, array $options ) : void
    {
        $builder
            ->add( 'fullname', TextType::class, [
                'label' => 'Nom complet',
            ] )
            ->add( 'phone', TextType::class, [
                'label' => 'Téléphone',
            ] )
            ->add( 'dateOfBirthday', DateType::class, [
                'label' => 'Date de naissance',
                'widget' => 'single_text',
                'html5' => false,
                'attr' => [
                    'class' => 'flatpickr-date-birthday',
                ]
            ] )
            ->add( 'avatarFile', VichFileType::class, [
                'label' => 'Avatar',
                'required' => false,
//                'mapped' => false,
                'attr' => [
                    'class' => 'form-control-file',
                ],
            ] )
//            ->add('avatar', VichFileType::class, [
//                'label' => 'Avatar',
//                'required' => false,
//                'mapped' => false,
//                'attr' => [
//                    'class' => 'form-control-file',
//                ],
//            ])
            ->add( 'email' );
    }

    public function configureOptions( OptionsResolver $resolver ) : void
    {
        $resolver->setDefaults( [
            'data_class' => User::class,
        ] );
    }
}