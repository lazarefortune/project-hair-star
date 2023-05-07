<?php

namespace App\Form;

use App\Entity\Permission;
use App\Entity\Role;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class RoleType extends AbstractType
{
    public function buildForm( FormBuilderInterface $builder, array $options ) : void
    {
        $builder
            ->add( 'name', TextType::class, [
                'label' => 'Nom',
                'required' => true,
            ] )
            ->add( 'displayName', TextType::class, [
                'label' => 'Nom affiché',
                'required' => true,
            ] )
            ->add( 'permissions', EntityType::class, [
                'class' => Permission::class,
                'choice_label' => 'name',
                'label' => 'Permissions',
                'required' => false,
                'multiple' => true,
                'expanded' => true,
            ] )
            ->add( 'submit', SubmitType::class, [
                'label' => 'Enregistrer',
                'attr' => ['class' => 'btn btn-primary'],
            ] );
    }

    public function configureOptions( OptionsResolver $resolver ) : void
    {
        $resolver->setDefaults( [
            'data_class' => Role::class,
        ] );
    }
}
