<?php

namespace App\DataFixtures;

use App\Entity\LandValueClaim;
use App\Entity\State;
use App\Entity\Department;
use DateTime;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Common\Persistence\ObjectManager;

class StateFixtures extends Fixture
{
    public function load(ObjectManager $manager)
    {

        // Deactivate SQLLogger.
        $config = $manager->getConnection()->getConfiguration();
        $logger = $config->getSQLLogger();
        $config->setSQLLogger(null);

        $delimiter = "|";
        // Load State data
        if (($handle = fopen("data/regions.txt", "r")) !== FALSE) {
            // Ignore first line. (header)
            fgetcsv($handle, 1000, $delimiter);

            while (($data = fgetcsv($handle, 1000, $delimiter)) !== FALSE) {
                $inseeCode = $data[0];
                $name = $data[1];

                $state = new State();
                $state->id = $inseeCode;
                $state->name = $name;

                $manager->persist($state);
            }

            $manager->flush();
            $manager->clear();
        }

        // Reactivate SQLLogger.
        $config->setSQLLogger($logger);
    }
}
