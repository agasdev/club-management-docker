<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20220825211143 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE club CHANGE budget budget BIGINT NOT NULL');
        $this->addSql('ALTER TABLE coach CHANGE salary salary BIGINT NOT NULL');
        $this->addSql('ALTER TABLE player CHANGE salary salary BIGINT NOT NULL');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE player CHANGE salary salary INT NOT NULL');
        $this->addSql('ALTER TABLE club CHANGE budget budget INT NOT NULL');
        $this->addSql('ALTER TABLE coach CHANGE salary salary INT NOT NULL');
    }
}
