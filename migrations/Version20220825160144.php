<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20220825160144 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE coach CHANGE date_of_birth date_of_birth DATE NOT NULL');
        $this->addSql('ALTER TABLE player CHANGE date_of_birth date_of_birth DATE NOT NULL');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE player CHANGE date_of_birth date_of_birth DATETIME NOT NULL');
        $this->addSql('ALTER TABLE coach CHANGE date_of_birth date_of_birth DATETIME NOT NULL');
    }
}
