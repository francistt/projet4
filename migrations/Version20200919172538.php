<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20200919172538 extends AbstractMigration
{
    public function getDescription() : string
    {
        return '';
    }

    public function up(Schema $schema) : void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE reservation CHANGE half_day half_day TINYINT(1) NOT NULL, CHANGE state state TINYINT(1) NOT NULL');
        $this->addSql('ALTER TABLE user CHANGE discount discount TINYINT(1) NOT NULL');
    }

    public function down(Schema $schema) : void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE reservation CHANGE half_day half_day TINYINT(1) DEFAULT \'0\' NOT NULL, CHANGE state state TINYINT(1) DEFAULT \'0\' NOT NULL');
        $this->addSql('ALTER TABLE user CHANGE discount discount TINYINT(1) DEFAULT \'0\' NOT NULL');
    }
}
