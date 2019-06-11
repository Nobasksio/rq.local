<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20190416140811 extends AbstractMigration
{
    public function getDescription() : string
    {
        return '';
    }

    public function up(Schema $schema) : void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() !== 'mysql', 'Migration can only be executed safely on \'mysql\'.');

        $this->addSql('CREATE TABLE product (id INT AUTO_INCREMENT NOT NULL, old_id_id INT DEFAULT NULL, project_id_id INT NOT NULL, name_work VARCHAR(255) NOT NULL, price DOUBLE PRECISION NOT NULL, cost_price DOUBLE PRECISION NOT NULL, weight INT NOT NULL, status TINYINT(1) NOT NULL, UNIQUE INDEX UNIQ_D34A04ADD9D054B1 (old_id_id), INDEX IDX_D34A04AD6C1197C9 (project_id_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci ENGINE = InnoDB');
        $this->addSql('ALTER TABLE product ADD CONSTRAINT FK_D34A04ADD9D054B1 FOREIGN KEY (old_id_id) REFERENCES old_product (id)');
        $this->addSql('ALTER TABLE product ADD CONSTRAINT FK_D34A04AD6C1197C9 FOREIGN KEY (project_id_id) REFERENCES project (id)');
    }

    public function down(Schema $schema) : void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() !== 'mysql', 'Migration can only be executed safely on \'mysql\'.');

        $this->addSql('DROP TABLE product');
    }
}
