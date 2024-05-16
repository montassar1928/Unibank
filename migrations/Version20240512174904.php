<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20240512174904 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE operation ADD ref INT DEFAULT NULL');
        $this->addSql('ALTER TABLE operation ADD CONSTRAINT FK_1981A66D146F3EA3 FOREIGN KEY (ref) REFERENCES virement_international (ref)');
        $this->addSql('CREATE INDEX IDX_1981A66D146F3EA3 ON operation (ref)');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE operation DROP FOREIGN KEY FK_1981A66D146F3EA3');
        $this->addSql('DROP INDEX IDX_1981A66D146F3EA3 ON operation');
        $this->addSql('ALTER TABLE operation DROP ref');
    }
}
