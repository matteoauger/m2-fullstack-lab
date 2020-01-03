<?php

namespace App\DataFixtures;

use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Common\Persistence\ObjectManager;

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

        foreach (self::$filenames as $filename) { 
            // Load data
            if (($handle = fopen($filename, "r")) !== FALSE) {
                // Ignore first line. (header)
                fgetcsv($handle, self::$lineSize, self::$delimiter);

                $index = 0;
                while (($data = fgetcsv($handle, self::$lineSize, self::$delimiter)) !== FALSE) {
                    if ($index > self::$limit) break;
                    $this->loadFromCSV($manager, $data, $index);
                    $index++;
                    if ($index % self::$batchSize == 0) {
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

    abstract public function loadFromCSV(ObjectManager $manager, $data, $index);
}
