<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20220825094957 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE TABLE club (id BINARY(16) NOT NULL COMMENT \'(DC2Type:uuid)\', created_at DATETIME DEFAULT CURRENT_TIMESTAMP NOT NULL COMMENT \'(DC2Type:datetime_immutable)\', updated_at DATETIME DEFAULT NULL on update CURRENT_TIMESTAMP COMMENT \'(DC2Type:datetime_immutable)\', name VARCHAR(100) NOT NULL, city VARCHAR(100) NOT NULL, country VARCHAR(100) NOT NULL, budget INT NOT NULL, PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE coach (id BINARY(16) NOT NULL COMMENT \'(DC2Type:uuid)\', club_id BINARY(16) DEFAULT NULL COMMENT \'(DC2Type:uuid)\', date_of_birth DATETIME NOT NULL, created_at DATETIME DEFAULT CURRENT_TIMESTAMP NOT NULL COMMENT \'(DC2Type:datetime_immutable)\', updated_at DATETIME DEFAULT NULL on update CURRENT_TIMESTAMP COMMENT \'(DC2Type:datetime_immutable)\', name VARCHAR(100) NOT NULL, surname VARCHAR(200) NOT NULL, city VARCHAR(100) NOT NULL, country VARCHAR(100) NOT NULL, salary INT NOT NULL, email VARCHAR(100) NOT NULL, UNIQUE INDEX UNIQ_3F596DCCE7927C74 (email), INDEX IDX_3F596DCC61190A32 (club_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE player (id BINARY(16) NOT NULL COMMENT \'(DC2Type:uuid)\', club_id BINARY(16) DEFAULT NULL COMMENT \'(DC2Type:uuid)\', date_of_birth DATETIME NOT NULL, created_at DATETIME DEFAULT CURRENT_TIMESTAMP NOT NULL COMMENT \'(DC2Type:datetime_immutable)\', updated_at DATETIME DEFAULT NULL on update CURRENT_TIMESTAMP COMMENT \'(DC2Type:datetime_immutable)\', name VARCHAR(100) NOT NULL, surname VARCHAR(200) NOT NULL, city VARCHAR(100) NOT NULL, country VARCHAR(100) NOT NULL, salary INT NOT NULL, email VARCHAR(100) NOT NULL, UNIQUE INDEX UNIQ_98197A65E7927C74 (email), INDEX IDX_98197A6561190A32 (club_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('ALTER TABLE coach ADD CONSTRAINT FK_3F596DCC61190A32 FOREIGN KEY (club_id) REFERENCES club (id)');
        $this->addSql('ALTER TABLE player ADD CONSTRAINT FK_98197A6561190A32 FOREIGN KEY (club_id) REFERENCES club (id)');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE coach DROP FOREIGN KEY FK_3F596DCC61190A32');
        $this->addSql('ALTER TABLE player DROP FOREIGN KEY FK_98197A6561190A32');
        $this->addSql('DROP TABLE club');
        $this->addSql('DROP TABLE coach');
        $this->addSql('DROP TABLE player');
    }
}
