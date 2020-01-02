<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20200102221910 extends AbstractMigration
{
    public function getDescription() : string
    {
        return 'Department table';
    }

    public function up(Schema $schema) : void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() !== 'postgresql', 'Migration can only be executed safely on \'postgresql\'.');

        $this->addSql('CREATE SEQUENCE department_id_seq INCREMENT BY 1 MINVALUE 1 START 1');
        $this->addSql('CREATE TABLE department (id INT NOT NULL, insee VARCHAR(255) NOT NULL, name VARCHAR(255) NOT NULL, state_insee INT NOT NULL, PRIMARY KEY(id))');
        $this->addSql('CREATE UNIQUE INDEX UNIQ_CD1DE18AFA1CBE2D ON department (insee)');
        $this->addSql('CREATE UNIQUE INDEX UNIQ_A393D2FBFA1CBE2D ON state (insee)');
    }

    public function down(Schema $schema) : void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() !== 'postgresql', 'Migration can only be executed safely on \'postgresql\'.');

        $this->addSql('DROP SEQUENCE department_id_seq CASCADE');
        $this->addSql('DROP TABLE department');
        $this->addSql('DROP INDEX UNIQ_A393D2FBFA1CBE2D');
    }
}
