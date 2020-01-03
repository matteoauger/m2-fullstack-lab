<?php

namespace App\DataFixtures;

use App\Entity\LandValueClaim;
use DateTime;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Common\Persistence\ObjectManager;

/*
 * [0] => Code service CH
 * [1] => Reference document
 * [2] => 1 Articles CGI
 * [3] => 2 Articles CGI
 * [4] => 3 Articles CGI
 * [5] => 4 Articles CGI
 * [6] => 5 Articles CGI
 * [7] => No disposition
 * [8] => Date mutation
 * [9] => Nature mutation
 * [10] => Valeur fonciere
 * [11] => No voie
 * [12] => B/T/Q
 * [13] => Type de voie
 * [14] => Code voie
 * [15] => Voie
 * [16] => Code postal
 * [17] => Commune
 * [18] => Code departement
 * [19] => Code commune
 * [20] => Prefixe de section
 * [21] => Section
 * [22] => No plan
 * [23] => No Volume
 * [24] => 1er lot
 * [25] => Surface Carrez du 1er lot
 * [26] => 2eme lot
 * [27] => Surface Carrez du 2eme lot
 * [28] => 3eme lot
 * [29] => Surface Carrez du 3eme lot
 * [30] => 4eme lot
 * [31] => Surface Carrez du 4eme lot
 * [32] => 5eme lot
 * [33] => Surface Carrez du 5eme lot
 * [34] => Nombre de lots
 * [35] => Code type local
 * [36] => Type local
 * [37] => Identifiant local
 * [38] => Surface reelle bati
 * [39] => Nombre pieces principales
 * [40] => Nature culture
 * [41] => Nature culture speciale
 * [42] => Surface terrain
 */
class LVCFixtures extends CSVFixture
{
    public function __construct()
    {
        parent::__construct(self::getFiles(), "|", 1000, 100, 1000);   
    }

    private function getFiles() {
        return array_map(function($year) {
            return "data/valeursfoncieres-".$year.".txt";
        }, [2015, 2016, 2017, 2018, 2019]);
    }

    /**
     * @see https://static.data.gouv.fr/resources/demandes-de-valeurs-foncieres/20191220-102114/notice-descriptive-du-fichier-dvf.pdf
     */
    public function loadFromCSV(ObjectManager $manager, $data, $index)
    {
        $mutationDate = $data[8];
        $mutationType = $data[9];
        $value = $data[10];
        $depCode = $data[18];
        $type = $data[36];
        $surface = $data[42];

        // Exclude invalid data.
        if (   $mutationDate == null
            || $mutationType == null 
            || $value == null
            || $value == "0"
            || $depCode == null
            || $type == null
            || $surface == null
            || $surface == "0"
            ) {
                return;
            }

        // Create LandValueClaim object.
        $lvc = new LandValueClaim();
        $lvc->mutationDate = DateTime::createFromFormat('d/m/Y', $mutationDate);
        $lvc->mutationType = $mutationType;
        $lvc->value = intval($value);
        $lvc->depCode = $depCode;
        $lvc->type = $type;
        $lvc->surface = intval($surface);

        // Insert in PHP cache.
        $manager->persist($lvc);
    }

    public function getDependencies()
    {
        return array(
            DepartmentFixtures::class,
        );
    }
}
