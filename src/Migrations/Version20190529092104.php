<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20190529092104 extends AbstractMigration
{
    public function getDescription() : string
    {
        return '';
    }

    public function up(Schema $schema) : void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() !== 'mysql', 'Migration can only be executed safely on \'mysql\'.');

        $this->addSql('CREATE TABLE ttk_component (ttk_id INT NOT NULL, component_id INT NOT NULL, INDEX IDX_C668935B5804C45B (ttk_id), INDEX IDX_C668935BE2ABAFFF (component_id), PRIMARY KEY(ttk_id, component_id)) DEFAULT CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci ENGINE = InnoDB');
        $this->addSql('ALTER TABLE ttk_component ADD CONSTRAINT FK_C668935B5804C45B FOREIGN KEY (ttk_id) REFERENCES ttk (id) ON DELETE CASCADE');
        $this->addSql('ALTER TABLE ttk_component ADD CONSTRAINT FK_C668935BE2ABAFFF FOREIGN KEY (component_id) REFERENCES component (id) ON DELETE CASCADE');
    }

    public function down(Schema $schema) : void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() !== 'mysql', 'Migration can only be executed safely on \'mysql\'.');

        $this->addSql('DROP TABLE ttk_component');
    }
}
