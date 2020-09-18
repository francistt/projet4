<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20200918173111 extends AbstractMigration
{
    public function getDescription() : string
    {
        return '';
    }

    public function up(Schema $schema) : void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE TABLE reservation (id INT AUTO_INCREMENT NOT NULL, last_name VARCHAR(255) NOT NULL, first_name VARCHAR(255) NOT NULL, email VARCHAR(255) NOT NULL, date DATE NOT NULL, uuid VARCHAR(255) NOT NULL, nb_ticket SMALLINT NOT NULL, half_day TINYINT(1) DEFAULT \'0\' NOT NULL, total INT NOT NULL, id_stripe VARCHAR(255) NOT NULL, state SMALLINT NOT NULL, PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE user (id INT AUTO_INCREMENT NOT NULL, reservation_id INT DEFAULT NULL, last_name VARCHAR(255) NOT NULL, first_name VARCHAR(255) NOT NULL, country VARCHAR(255) NOT NULL, birthdate DATE NOT NULL, discount SMALLINT NOT NULL, price INT NOT NULL, INDEX IDX_8D93D649B83297E7 (reservation_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('ALTER TABLE user ADD CONSTRAINT FK_8D93D649B83297E7 FOREIGN KEY (reservation_id) REFERENCES reservation (id)');
    }

    public function down(Schema $schema) : void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE user DROP FOREIGN KEY FK_8D93D649B83297E7');
        $this->addSql('DROP TABLE reservation');
        $this->addSql('DROP TABLE user');
    }
}
