<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20200924164833 extends AbstractMigration
{
    public function getDescription() : string
    {
        return '';
    }

    public function up(Schema $schema) : void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE reservation ADD reserver_id INT DEFAULT NULL');
        $this->addSql('ALTER TABLE reservation ADD CONSTRAINT FK_42C8495544A67F3 FOREIGN KEY (reserver_id) REFERENCES user (id)');
        $this->addSql('CREATE INDEX IDX_42C8495544A67F3 ON reservation (reserver_id)');
    }

    public function down(Schema $schema) : void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE reservation DROP FOREIGN KEY FK_42C8495544A67F3');
        $this->addSql('DROP INDEX IDX_42C8495544A67F3 ON reservation');
        $this->addSql('ALTER TABLE reservation DROP reserver_id');
    }
}
