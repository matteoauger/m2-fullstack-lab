<?php

namespace App\DataFixtures\ORM;

use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Common\Persistence\ObjectManager;
use Doctrine\ORM\EntityManager;

abstract class CSVFixture extends Fixture
{
    private $filenames;
    private $delimiter;
    private $lineSize;
    private $batchSize;
    private $limit;

    protected function __construct($filenames, $delimiter = "|", $lineSize = 1000, $batchSize = 50, $limit = PHP_INT_MAX) {
        $this->filenames = is_array($filenames) ? $filenames : [$filenames];
        $this->delimiter = $delimiter;
        $this->lineSize = $lineSize;
        $this->batchSize = $batchSize;
        $this->limit = $limit;
    }

    public function load(ObjectManager $manager) 
    {
        // Deactivate SQLLogger.
        $config = $manager->getConnection()->getConfiguration();
        $logger = $config->getSQLLogger();
        $config->setSQLLogger(null);

        foreach ($this->filenames as $filename) { 
            // Load data
            if (($handle = @fopen($filename, "r")) !== FALSE) {
                // Ignore first line. (header)
                fgetcsv($handle, $this->lineSize, $this->delimiter);

                $index = 0;
                while ($index < $this->limit && ($data = @fgetcsv($handle, $this->lineSize, $this->delimiter)) !== FALSE) {
                    $this->loadFromCSV($manager, $data, $index);
                    $index++;
                    if ($index % $this->batchSize == 0) {
                        // Send to DB and clear cache.
                        $manager->flush();
                        $manager->clear();
                    }
                }
                $manager->flush();
                $manager->clear();
            }
        }

        // Reactivate SQLLogger.
        $config->setSQLLogger($logger);
    }

    abstract public function loadFromCSV(EntityManager $manager, $data, $index);
}
