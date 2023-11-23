<?php

namespace App\Form;

use App\Form\Type\SwitchType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\Extension\Core\Type\ColorType;
use Symfony\Component\Form\Extension\Core\Type\FileType;
use Symfony\Component\Form\Extension\Core\Type\NumberType;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\HttpFoundation\File\UploadedFile;

/**
 * Génère un formulaire de manière automatique en lisant les propriétés d'un objet.
 */
class AutomaticForm extends AbstractType
{
    final public const TYPES = [
        'string' => TextType::class,
        'bool' => SwitchType::class,
        'int' => NumberType::class,
        'float' => NumberType::class,
        \DateTimeInterface::class => TextType::class,
        UploadedFile::class => FileType::class,
    ];

    final public const NAMES = [
        'content' => TextareaType::class,
        'description' => TextareaType::class,
        'short' => TextareaType::class,
        'color' => ColorType::class,
        'links' => TextareaType::class,
    ];

    public function buildForm( FormBuilderInterface $builder, array $options ) : void
    {
        $data = $options['data'];
        $refClass = new \ReflectionClass( $data );
        $classProperties = $refClass->getProperties( \ReflectionProperty::IS_PUBLIC );
        foreach ( $classProperties as $property ) {
            $name = $property->getName();
            /** @var \ReflectionNamedType|null $type */
            $type = $property->getType();
            if ( null === $type ) {
                return;
            }
            if ( 'requirements' === $name ) {
                $builder->add( 'requirements', ChoiceType::class, [
                    'multiple' => true,
                ] );
            }
            // Input spécifique au niveau
            if ( 'level' === $name ) {
                $builder->add( $name, ChoiceType::class, [
                    'required' => true,
                    'choices' => array_flip( Formation::$levels ),
                ] );
                // Input spécifique au nom du champs
            } elseif ( array_key_exists( $name, self::NAMES ) ) {
                $builder->add( $name, self::NAMES[$name], [
                    'required' => false,
                ] );
            } elseif ( array_key_exists( $type->getName(), self::TYPES ) ) {
                $builder->add( $name, self::TYPES[$type->getName()], [
                    'required' => !$type->allowsNull() && 'bool' !== $type->getName(),
                ] );
            } else {
                throw new \RuntimeException( sprintf( 'Impossible de trouver le champs associé au type %s dans %s::%s', $type->getName(), $data::class, $name ) );
            }
        }
    }
}