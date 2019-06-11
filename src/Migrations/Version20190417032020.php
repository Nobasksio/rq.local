<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20190417032020 extends AbstractMigration
{
    public function getDescription() : string
    {
        return '';
    }

    public function up(Schema $schema) : void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() !== 'mysql', 'Migration can only be executed safely on \'mysql\'.');

        $this->addSql('ALTER TABLE product DROP FOREIGN KEY FK_D34A04AD6C1197C9');
        $this->addSql('ALTER TABLE product DROP FOREIGN KEY FK_D34A04ADD9D054B1');
        $this->addSql('DROP INDEX UNIQ_D34A04ADD9D054B1 ON product');
        $this->addSql('DROP INDEX IDX_D34A04AD6C1197C9 ON product');
        $this->addSql('ALTER TABLE product CHANGE old_id_id old_product_id INT DEFAULT NULL, CHANGE project_id_id project_id INT NOT NULL');
        $this->addSql('ALTER TABLE product ADD CONSTRAINT FK_D34A04ADF9A75104 FOREIGN KEY (old_product_id) REFERENCES old_product (id)');
        $this->addSql('ALTER TABLE product ADD CONSTRAINT FK_D34A04AD166D1F9C FOREIGN KEY (project_id) REFERENCES project (id)');
        $this->addSql('CREATE UNIQUE INDEX UNIQ_D34A04ADF9A75104 ON product (old_product_id)');
        $this->addSql('CREATE INDEX IDX_D34A04AD166D1F9C ON product (project_id)');
    }

    public function down(Schema $schema) : void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() !== 'mysql', 'Migration can only be executed safely on \'mysql\'.');

        $this->addSql('ALTER TABLE product DROP FOREIGN KEY FK_D34A04ADF9A75104');
        $this->addSql('ALTER TABLE product DROP FOREIGN KEY FK_D34A04AD166D1F9C');
        $this->addSql('DROP INDEX UNIQ_D34A04ADF9A75104 ON product');
        $this->addSql('DROP INDEX IDX_D34A04AD166D1F9C ON product');
        $this->addSql('ALTER TABLE product CHANGE old_product_id old_id_id INT DEFAULT NULL, CHANGE project_id project_id_id INT NOT NULL');
        $this->addSql('ALTER TABLE product ADD CONSTRAINT FK_D34A04AD6C1197C9 FOREIGN KEY (project_id_id) REFERENCES project (id) ON UPDATE NO ACTION ON DELETE NO ACTION');
        $this->addSql('ALTER TABLE product ADD CONSTRAINT FK_D34A04ADD9D054B1 FOREIGN KEY (old_id_id) REFERENCES old_product (id) ON UPDATE NO ACTION ON DELETE NO ACTION');
        $this->addSql('CREATE UNIQUE INDEX UNIQ_D34A04ADD9D054B1 ON product (old_id_id)');
        $this->addSql('CREATE INDEX IDX_D34A04AD6C1197C9 ON product (project_id_id)');
    }
}
