<?php

declare(strict_types=1);

namespace App\Migrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20190131152551 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->abortIf('sqlite' !== $this->connection->getDatabasePlatform()->getName(), 'Migration can only be executed safely on \'sqlite\'.');

        $this->addSql('CREATE TABLE tokens (id VARCHAR(255) NOT NULL, user_id VARCHAR(255) DEFAULT NULL, PRIMARY KEY(id))');
        $this->addSql('CREATE INDEX IDX_AA5A118EA76ED395 ON tokens (user_id)');
        $this->addSql('CREATE TABLE token_permissions (token_id VARCHAR(255) NOT NULL, permission_id VARCHAR(255) NOT NULL, PRIMARY KEY(token_id, permission_id))');
        $this->addSql('CREATE INDEX IDX_1DC9EF541DEE7B9 ON token_permissions (token_id)');
        $this->addSql('CREATE INDEX IDX_1DC9EF5FED90CCA ON token_permissions (permission_id)');
        $this->addSql('CREATE TABLE users (id VARCHAR(255) NOT NULL, profile_id VARCHAR(255) DEFAULT NULL, username VARCHAR(255) NOT NULL, password VARCHAR(255) NOT NULL, PRIMARY KEY(id))');
        $this->addSql('CREATE INDEX IDX_1483A5E9CCFA12B8 ON users (profile_id)');
        $this->addSql('CREATE TABLE profiles (id VARCHAR(255) NOT NULL, label VARCHAR(255) NOT NULL, PRIMARY KEY(id))');
        $this->addSql('CREATE TABLE profile_permissions (profile_id VARCHAR(255) NOT NULL, permission_id VARCHAR(255) NOT NULL, PRIMARY KEY(profile_id, permission_id))');
        $this->addSql('CREATE INDEX IDX_38F08A11CCFA12B8 ON profile_permissions (profile_id)');
        $this->addSql('CREATE INDEX IDX_38F08A11FED90CCA ON profile_permissions (permission_id)');
        $this->addSql('CREATE TABLE permissions (id VARCHAR(255) NOT NULL, label VARCHAR(255) NOT NULL, PRIMARY KEY(id))');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->abortIf('sqlite' !== $this->connection->getDatabasePlatform()->getName(), 'Migration can only be executed safely on \'sqlite\'.');

        $this->addSql('DROP TABLE tokens');
        $this->addSql('DROP TABLE token_permissions');
        $this->addSql('DROP TABLE users');
        $this->addSql('DROP TABLE profiles');
        $this->addSql('DROP TABLE profile_permissions');
        $this->addSql('DROP TABLE permissions');
    }
}
