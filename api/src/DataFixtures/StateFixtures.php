<?php

namespace App\DataFixtures;

use App\Entity\State;
use Doctrine\Common\Persistence\ObjectManager;

class StateFixtures extends CSVFixture
{
    public function __construct()
    {
        parent::__construct("data/regions.txt");        
    }

    public function loadFromCSV(ObjectManager $manager, $data, $index)
    {
        $inseeCode = $data[0];
        $name = $data[1];

        $state = new State();
        $state->id = $inseeCode;
        $state->name = $name;

        $manager->persist($state);
    }

    public function getDependencies()
    {
        return array(
            DepartmentFixtures::class,
        );
    }
}
