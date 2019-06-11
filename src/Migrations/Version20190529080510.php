<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20190529080510 extends AbstractMigration
{
    public function getDescription() : string
    {
        return '';
    }

    public function up(Schema $schema) : void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() !== 'mysql', 'Migration can only be executed safely on \'mysql\'.');

        $this->addSql('CREATE TABLE degustation_score (id INT AUTO_INCREMENT NOT NULL, product_id INT DEFAULT NULL, user_id INT NOT NULL, taste_score INT NOT NULL, view_score INT NOT NULL, concept_score INT NOT NULL, price_score DOUBLE PRECISION NOT NULL, comment VARCHAR(255) DEFAULT NULL, date_update DATETIME NOT NULL, INDEX IDX_588248D94584665A (product_id), INDEX IDX_588248D9A76ED395 (user_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci ENGINE = InnoDB');
        $this->addSql('ALTER TABLE degustation_score ADD CONSTRAINT FK_588248D94584665A FOREIGN KEY (product_id) REFERENCES product (id)');
        $this->addSql('ALTER TABLE degustation_score ADD CONSTRAINT FK_588248D9A76ED395 FOREIGN KEY (user_id) REFERENCES user (id)');
        $this->addSql('ALTER TABLE ttk ADD technology LONGTEXT DEFAULT NULL, ADD comment LONGTEXT DEFAULT NULL');
    }

    public function down(Schema $schema) : void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() !== 'mysql', 'Migration can only be executed safely on \'mysql\'.');

        $this->addSql('DROP TABLE degustation_score');
        $this->addSql('ALTER TABLE ttk DROP technology, DROP comment');
    }
}
