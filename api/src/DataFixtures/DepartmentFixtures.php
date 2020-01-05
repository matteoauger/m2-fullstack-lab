<?php

namespace App\DataFixtures;

use App\DataFixtures\ORM\CSVFixture;
use Doctrine\Common\DataFixtures\DependentFixtureInterface;
use App\Entity\Department;
use App\Entity\State;
use Doctrine\Common\Persistence\ObjectManager;

class DepartmentFixtures extends CSVFixture implements DependentFixtureInterface
{
    function __construct()
    {
        parent::__construct(array("data/departments.txt"));
    }

    public function loadFromCSV(ObjectManager $manager, $data, $index)
    {
        $inseeCode = $data[0];
        $name = $data[1];
        $stateInsee = $data[2];

        $department = new Department();
        $department->id = $inseeCode;
        $department->name = $name;
        $department->state = $manager->getReference(State::class, $stateInsee);

        $manager->persist($department);
    }


    public function getDependencies()
    {
        return array(
            StateFixtures::class,
        );
    }
}
