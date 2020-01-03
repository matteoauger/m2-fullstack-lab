<?php

namespace App\DataFixtures;

use App\Entity\Department;
use Doctrine\Common\Persistence\ObjectManager;

class DepartmentFixtures extends CSVFixture
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
        $department->stateInsee = $stateInsee;

        $manager->persist($department);
    }
}
