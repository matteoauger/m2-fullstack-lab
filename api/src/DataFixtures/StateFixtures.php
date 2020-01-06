<?php

namespace App\DataFixtures;

use App\DataFixtures\ORM\CSVFixture;
use App\Entity\State;
use Doctrine\ORM\EntityManager;

class StateFixtures extends CSVFixture
{
    public function __construct()
    {
        parent::__construct("data/regions.txt");        
    }

    public function loadFromCSV(EntityManager $manager, $data, $index)
    {
        $inseeCode = $data[0];
        $name = $data[1];

        $state = new State();
        $state->id = $inseeCode;
        $state->name = $name;

        $manager->persist($state);
    }
}
