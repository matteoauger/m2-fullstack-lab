<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20200102225758 extends AbstractMigration
{
    public function getDescription() : string
    {
        return 'Delete state and department auto-generated ids';
    }

    public function up(Schema $schema) : void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() !== 'postgresql', 'Migration can only be executed safely on \'postgresql\'.');

        $this->addSql('DROP SEQUENCE state_id_seq CASCADE');
        $this->addSql('DROP SEQUENCE department_id_seq CASCADE');
        $this->addSql('DROP INDEX uniq_a393d2fbfa1cbe2d');
        $this->addSql('ALTER TABLE state DROP insee');
        $this->addSql('DROP INDEX uniq_cd1de18afa1cbe2d');
        $this->addSql('ALTER TABLE department DROP insee');
        $this->addSql('ALTER TABLE department ALTER id TYPE VARCHAR(255)');
        $this->addSql('ALTER TABLE department ALTER id DROP DEFAULT');
    }

    public function down(Schema $schema) : void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() !== 'postgresql', 'Migration can only be executed safely on \'postgresql\'.');

        $this->addSql('CREATE SCHEMA public');
        $this->addSql('CREATE SEQUENCE state_id_seq INCREMENT BY 1 MINVALUE 1 START 1');
        $this->addSql('CREATE SEQUENCE department_id_seq INCREMENT BY 1 MINVALUE 1 START 1');
        $this->addSql('ALTER TABLE state ADD insee INT NOT NULL');
        $this->addSql('CREATE UNIQUE INDEX uniq_a393d2fbfa1cbe2d ON state (insee)');
        $this->addSql('ALTER TABLE department ADD insee VARCHAR(255) NOT NULL');
        $this->addSql('ALTER TABLE department ALTER id TYPE INT');
        $this->addSql('ALTER TABLE department ALTER id DROP DEFAULT');
        $this->addSql('CREATE UNIQUE INDEX uniq_cd1de18afa1cbe2d ON department (insee)');
    }
}
