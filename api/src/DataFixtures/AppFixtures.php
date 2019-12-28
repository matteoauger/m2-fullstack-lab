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
        $batchSize = 100;
        $delimiter = "|";
        $years = [2015, 2016, 2017, 2018, 2019];
        
        // Deactivate SQLLogger
        $config = $manager->getConnection()->getConfiguration();
        $logger = $config->getSQLLogger();
        $config->setSQLLogger(null);

        // Load data [2015-2019]
        foreach ($years as $year) {
            echo "Year ".$year."... ";
            $start = time();
            // Read file
            if (($handle = fopen("data/valeursfoncieres-".$year.".txt", "r")) !== FALSE) {
                // Ignore first line (header)
                fgetcsv($handle, 1000, $delimiter);
                // Load each lines
                $i = 0;
                while (($data = fgetcsv($handle, 1000, $delimiter)) !== FALSE) {
                    // Format data.
                    $mutationDate = $data[8] ?: "01/01/".$year;
                    $mutationType = $data[9] ?: "Vente";
                    $value = intval($data[10] ?: "100");
                    $depCode = $data[18] ?: "00";
                    $surface = intval($data[38] ?: "100.00");

                    // Create LandValueClaim object.
                    $lvc = new LandValueClaim();
                    $lvc->mutationDate = DateTime::createFromFormat('d/m/Y', $mutationDate);
                    $lvc->mutationType = $mutationType;
                    $lvc->value = $value;
                    $lvc->depCode = $depCode;
                    $lvc->surface = $surface;

                    // Insert in PHP cache.
                    $manager->persist($lvc);
                    if ($i % $batchSize == 0) {
                        // Send to DB and clear cache.
                        $manager->flush();
                        $manager->clear();
                    }
                    $i++;
                    // /* Partial loading (fast) */ if ($i > 1000) break;
                }
                // Send to DB and clear cache (last items).
                $manager->flush();
                $manager->clear();
                fclose($handle);
            }
            $end = time();
            echo "(".$i." items(s) loaded in ".($end - $start)."s)\n";
        }
        // Reactivate SQLLogger
        $config->setSQLLogger($logger);
    }
}
