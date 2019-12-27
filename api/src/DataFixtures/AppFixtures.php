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

class AppFixtures extends Fixture
{
    public function load(ObjectManager $manager)
    {
        // Read file
        $handle = fopen("data/valeursfoncieres-2019.txt", "r");
        if ($handle) {
            // Ignore first line
            fgets($handle, 4096);
            // Get each lines
            while (($buffer = fgets($handle, 4096)) !== false) {
                $data = explode("|", $buffer, 43);
                    $mutationDate = $data[8] ?: "01/01/2019";
                    $mutationType = $data[9] ?: "Vente";
                    $value = intval($data[10] ?: "100");
                    $postCode = $data[16] ?: "0000";
                    $city = $data[17] ?: "UNKNOWN";
                    $stateCode = $data[18] ?: "00";
                    $surface = intval($data[38] ?: "100.00");

                    $lvc = new LandValueClaim();
                    $lvc->mutationDate = DateTime::createFromFormat('d/m/Y', $mutationDate);
                    $lvc->mutationType = $mutationType;
                    $lvc->value = $value;
                    $lvc->postCode = $postCode;
                    $lvc->city = $city;
                    $lvc->stateCode = $stateCode;
                    $lvc->surface = $surface;
                    
                    $manager->persist($lvc);
            }
            if (!feof($handle)) {
                echo "Error: fgets() has failed\n";
            }
            fclose($handle); 
        }
        $manager->flush();
    }
}
