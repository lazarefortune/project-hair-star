<?php

namespace App\DataFixtures;

use App\Entity\Option;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Persistence\ObjectManager;
use Symfony\Component\Form\Extension\Core\Type\TextType;

class OptionFixtures extends Fixture
{

    public function load( ObjectManager $manager )
    {
        $options[] = new Option( 'Titre du site', 'site_title', 'Mon super site', TextType::class );
        $options[] = new Option( 'Description du site', 'site_description', 'Description de mon super site', TextType::class );

        foreach ( $options as $option ) {
            $manager->persist( $option );
        }

        $manager->flush();
    }
}