<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20190412023000 extends AbstractMigration
{
    public function getDescription() : string
    {
        return '';
    }

    public function up(Schema $schema) : void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() !== 'mysql', 'Migration can only be executed safely on \'mysql\'.');

        $this->addSql('ALTER TABLE old_product ADD category_id INT DEFAULT NULL');
        $this->addSql('ALTER TABLE old_product ADD CONSTRAINT FK_A0DFDDFD12469DE2 FOREIGN KEY (category_id) REFERENCES old_category (id)');
        $this->addSql('CREATE INDEX IDX_A0DFDDFD12469DE2 ON old_product (category_id)');
    }

    public function down(Schema $schema) : void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() !== 'mysql', 'Migration can only be executed safely on \'mysql\'.');

        $this->addSql('ALTER TABLE old_product DROP FOREIGN KEY FK_A0DFDDFD12469DE2');
        $this->addSql('DROP INDEX IDX_A0DFDDFD12469DE2 ON old_product');
        $this->addSql('ALTER TABLE old_product DROP category_id');
    }
}
