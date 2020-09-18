<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20200919141219 extends AbstractMigration
{
    public function getDescription() : string
    {
        return '';
    }

    public function up(Schema $schema) : void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE reservation ADD created_at DATETIME NOT NULL, DROP date, CHANGE nb_ticket nb_ticket INT NOT NULL, CHANGE id_stripe id_stripe VARCHAR(255) DEFAULT NULL, CHANGE state state TINYINT(1) DEFAULT \'0\' NOT NULL');
        $this->addSql('ALTER TABLE user CHANGE discount discount TINYINT(1) DEFAULT \'0\' NOT NULL');
    }

    public function down(Schema $schema) : void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE reservation ADD date DATE NOT NULL, DROP created_at, CHANGE nb_ticket nb_ticket SMALLINT NOT NULL, CHANGE id_stripe id_stripe VARCHAR(255) CHARACTER SET utf8mb4 NOT NULL COLLATE `utf8mb4_unicode_ci`, CHANGE state state SMALLINT NOT NULL');
        $this->addSql('ALTER TABLE user CHANGE discount discount SMALLINT NOT NULL');
    }
}
