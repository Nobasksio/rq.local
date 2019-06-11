<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20190417043240 extends AbstractMigration
{
    public function getDescription() : string
    {
        return '';
    }

    public function up(Schema $schema) : void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() !== 'mysql', 'Migration can only be executed safely on \'mysql\'.');

        $this->addSql('ALTER TABLE old_product DROP FOREIGN KEY FK_A0DFDDFD98BB8596');
        $this->addSql('DROP INDEX UNIQ_A0DFDDFD98BB8596 ON old_product');
        $this->addSql('ALTER TABLE old_product CHANGE new_product_id product_id INT DEFAULT NULL');
        $this->addSql('ALTER TABLE old_product ADD CONSTRAINT FK_A0DFDDFD4584665A FOREIGN KEY (product_id) REFERENCES product (id)');
        $this->addSql('CREATE UNIQUE INDEX UNIQ_A0DFDDFD4584665A ON old_product (product_id)');
    }

    public function down(Schema $schema) : void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() !== 'mysql', 'Migration can only be executed safely on \'mysql\'.');

        $this->addSql('ALTER TABLE old_product DROP FOREIGN KEY FK_A0DFDDFD4584665A');
        $this->addSql('DROP INDEX UNIQ_A0DFDDFD4584665A ON old_product');
        $this->addSql('ALTER TABLE old_product CHANGE product_id new_product_id INT DEFAULT NULL');
        $this->addSql('ALTER TABLE old_product ADD CONSTRAINT FK_A0DFDDFD98BB8596 FOREIGN KEY (new_product_id) REFERENCES product (id) ON UPDATE NO ACTION ON DELETE NO ACTION');
        $this->addSql('CREATE UNIQUE INDEX UNIQ_A0DFDDFD98BB8596 ON old_product (new_product_id)');
    }
}
