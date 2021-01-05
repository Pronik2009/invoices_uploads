<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20210103133153 extends AbstractMigration
{
    public function getDescription() : string
    {
        return '';
    }

    public function up(Schema $schema) : void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE TABLE uploads (id INT AUTO_INCREMENT NOT NULL, date DATETIME NOT NULL, user INT DEFAULT NULL, filename VARCHAR(255) NOT NULL, PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('ALTER TABLE invoices ADD uploads_id INT DEFAULT NULL');
        $this->addSql('ALTER TABLE invoices ADD CONSTRAINT FK_6A2F2F95B66372A5 FOREIGN KEY (uploads_id) REFERENCES uploads (id)');
        $this->addSql('CREATE INDEX IDX_6A2F2F95B66372A5 ON invoices (uploads_id)');
    }

    public function down(Schema $schema) : void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE invoices DROP FOREIGN KEY FK_6A2F2F95B66372A5');
        $this->addSql('DROP TABLE uploads');
        $this->addSql('DROP INDEX IDX_6A2F2F95B66372A5 ON invoices');
        $this->addSql('ALTER TABLE invoices DROP uploads_id');
    }
}
