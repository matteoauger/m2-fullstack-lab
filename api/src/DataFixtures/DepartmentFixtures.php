<?php

namespace App\DataFixtures;

use App\Entity\Department;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Common\Persistence\ObjectManager;

class LVCFixtures extends Fixture
{
    public function load(ObjectManager $manager) 
    {
        // Deactivate SQLLogger.
        $config = $manager->getConnection()->getConfiguration();
        $logger = $config->getSQLLogger();
        $config->setSQLLogger(null);

        $delimiter = "|";
        // Load State data
        if (($handle = fopen("data/departments.txt", "r")) !== FALSE) {
            // Ignore first line. (header)
            fgetcsv($handle, 1000, $delimiter);

            while (($data = fgetcsv($handle, 1000, $delimiter)) !== FALSE) {
                $inseeCode = $data[0];
                $name = $data[1];
                $stateInsee = $data[2];

                $department = new Department();
                $department->id = $inseeCode;
                $department->name = $name;
                $department->stateInsee = $stateInsee;

                $manager->persist($department);
            }

            $manager->flush();
            $manager->clear();
        }

        // Reactivate SQLLogger.
        $config->setSQLLogger($logger);
    }
}
