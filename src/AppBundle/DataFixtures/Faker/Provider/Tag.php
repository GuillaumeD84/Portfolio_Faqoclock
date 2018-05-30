<?php

namespace AppBundle\DataFixtures\Faker\Provider;

class Tag extends \Faker\Provider\Base
{
    protected static $tags = array(
        'Application d\'Internet‎',
        'Application de base de données‎',
        'Apprentissage électronique‎',
        'Art numérique‎',
        'Bio-informatique‎',
        'Bureautique‎',
        'Calcul informatique‎',
        'Calcul numérique‎',
        'CAO‎',
        'Chémoinformatique‎',
        'Chimie numérique‎',
        'Conception et fabrication assistées par ordinateur‎',
        'Domotique‎',
        'Émulation‎',
        'Imagerie numérique‎',
        'Impression‎',
        'Informatique de gestion‎',
        'Informatique distribuée‎',
        'Informatique médicale‎',
        'Ingénierie des connaissances‎',
        'Jeu électronique‎',
        'Linguistique informatique‎',
        'Logiciel d\'affichage en sciences de la Terre‎',
        'Multimédia‎',
        'Publication assistée par ordinateur‎',
        'Recherche d\'information‎',
        // 'Technologies de l\'information et de la communication pour l\'enseignement‎',
        'Vidéo numérique‎',
        'Virtualisation‎',
        'Vote électronique‎',
    );

    public static function tag()
    {
        return static::randomElement(static::$tags);
    }
}
