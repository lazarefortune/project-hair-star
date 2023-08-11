<?php

namespace App\Form;

use App\Entity\CategoryService;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class CategoryServiceType extends AbstractType
{
    public function buildForm( FormBuilderInterface $builder, array $options ) : void
    {
        $builder
            ->add( 'name', TextType::class, [
                'label' => 'Nom de la catégorie',
                'attr' => [
                    'class' => '',
                ],
                'label_attr' => [
                    'class' => 'label',
                ],
            ] )
            ->add( 'description', TextType::class, [
                'label' => 'Description de la catégorie',
                'attr' => [
                    'class' => '',
                ],
                'label_attr' => [
                    'class' => 'label',
                ],
            ] )
            ->add( 'isActive', null, [
                'label' => 'Actif',
                'attr' => [
                    'class' => 'form-check-input',
                ],
                'label_attr' => [
                    'class' => 'label',
                ],
            ] );
    }

    public function configureOptions( OptionsResolver $resolver ) : void
    {
        $resolver->setDefaults( [
            'data_class' => CategoryService::class,
        ] );
    }
}
