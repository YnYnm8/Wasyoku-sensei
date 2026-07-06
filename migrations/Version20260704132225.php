<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20260704132225 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('DROP TABLE user');
        $this->addSql('ALTER TABLE condiment ADD COLUMN url VARCHAR(255) DEFAULT NULL');
        $this->addSql('ALTER TABLE condiment ADD COLUMN image_url VARCHAR(255) NOT NULL');
        $this->addSql('CREATE TEMPORARY TABLE __temp__favorite AS SELECT id, saved_at, recipe_id FROM favorite');
        $this->addSql('DROP TABLE favorite');
        $this->addSql('CREATE TABLE favorite (id INTEGER PRIMARY KEY AUTOINCREMENT NOT NULL, saved_at DATETIME NOT NULL, recipe_id INTEGER NOT NULL, CONSTRAINT FK_68C58ED959D8A214 FOREIGN KEY (recipe_id) REFERENCES recipe (id) ON UPDATE NO ACTION ON DELETE NO ACTION NOT DEFERRABLE INITIALLY IMMEDIATE)');
        $this->addSql('INSERT INTO favorite (id, saved_at, recipe_id) SELECT id, saved_at, recipe_id FROM __temp__favorite');
        $this->addSql('DROP TABLE __temp__favorite');
        $this->addSql('CREATE INDEX IDX_68C58ED959D8A214 ON favorite (recipe_id)');
        $this->addSql('CREATE TEMPORARY TABLE __temp__shopping_list AS SELECT id, memo, created_at FROM shopping_list');
        $this->addSql('DROP TABLE shopping_list');
        $this->addSql('CREATE TABLE shopping_list (id INTEGER PRIMARY KEY AUTOINCREMENT NOT NULL, memo VARCHAR(255) DEFAULT NULL, created_at DATETIME NOT NULL)');
        $this->addSql('INSERT INTO shopping_list (id, memo, created_at) SELECT id, memo, created_at FROM __temp__shopping_list');
        $this->addSql('DROP TABLE __temp__shopping_list');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE TABLE user (id INTEGER PRIMARY KEY AUTOINCREMENT NOT NULL, email VARCHAR(180) NOT NULL COLLATE "BINARY", roles CLOB NOT NULL COLLATE "BINARY", password VARCHAR(255) NOT NULL COLLATE "BINARY", username VARCHAR(255) NOT NULL COLLATE "BINARY")');
        $this->addSql('CREATE UNIQUE INDEX UNIQ_IDENTIFIER_EMAIL ON user (email)');
        $this->addSql('CREATE TEMPORARY TABLE __temp__condiment AS SELECT id, name, explanation, composition, use, note FROM condiment');
        $this->addSql('DROP TABLE condiment');
        $this->addSql('CREATE TABLE condiment (id INTEGER PRIMARY KEY AUTOINCREMENT NOT NULL, name VARCHAR(255) NOT NULL, explanation VARCHAR(255) NOT NULL, composition VARCHAR(255) DEFAULT NULL, use VARCHAR(255) NOT NULL, note VARCHAR(255) DEFAULT NULL)');
        $this->addSql('INSERT INTO condiment (id, name, explanation, composition, use, note) SELECT id, name, explanation, composition, use, note FROM __temp__condiment');
        $this->addSql('DROP TABLE __temp__condiment');
        $this->addSql('CREATE TEMPORARY TABLE __temp__favorite AS SELECT id, saved_at, recipe_id FROM favorite');
        $this->addSql('DROP TABLE favorite');
        $this->addSql('CREATE TABLE favorite (id INTEGER PRIMARY KEY AUTOINCREMENT NOT NULL, saved_at DATETIME NOT NULL, recipe_id INTEGER NOT NULL, user_id INTEGER NOT NULL, CONSTRAINT FK_68C58ED959D8A214 FOREIGN KEY (recipe_id) REFERENCES recipe (id) NOT DEFERRABLE INITIALLY IMMEDIATE, CONSTRAINT FK_68C58ED9A76ED395 FOREIGN KEY (user_id) REFERENCES user (id) ON UPDATE NO ACTION ON DELETE NO ACTION NOT DEFERRABLE INITIALLY IMMEDIATE)');
        $this->addSql('INSERT INTO favorite (id, saved_at, recipe_id) SELECT id, saved_at, recipe_id FROM __temp__favorite');
        $this->addSql('DROP TABLE __temp__favorite');
        $this->addSql('CREATE INDEX IDX_68C58ED959D8A214 ON favorite (recipe_id)');
        $this->addSql('CREATE INDEX IDX_68C58ED9A76ED395 ON favorite (user_id)');
        $this->addSql('CREATE TEMPORARY TABLE __temp__shopping_list AS SELECT id, memo, created_at FROM shopping_list');
        $this->addSql('DROP TABLE shopping_list');
        $this->addSql('CREATE TABLE shopping_list (id INTEGER PRIMARY KEY AUTOINCREMENT NOT NULL, memo VARCHAR(255) DEFAULT NULL, created_at DATETIME NOT NULL, user_id INTEGER NOT NULL, CONSTRAINT FK_3DC1A459A76ED395 FOREIGN KEY (user_id) REFERENCES user (id) ON UPDATE NO ACTION ON DELETE NO ACTION NOT DEFERRABLE INITIALLY IMMEDIATE)');
        $this->addSql('INSERT INTO shopping_list (id, memo, created_at) SELECT id, memo, created_at FROM __temp__shopping_list');
        $this->addSql('DROP TABLE __temp__shopping_list');
        $this->addSql('CREATE INDEX IDX_3DC1A459A76ED395 ON shopping_list (user_id)');
    }
}
