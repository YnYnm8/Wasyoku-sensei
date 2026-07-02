<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20260702122705 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE TABLE asian_shop (id INTEGER PRIMARY KEY AUTOINCREMENT NOT NULL, name VARCHAR(255) NOT NULL, postcode VARCHAR(255) NOT NULL, address VARCHAR(255) NOT NULL, telephone VARCHAR(255) NOT NULL, explanation_service CLOB DEFAULT NULL)');
        $this->addSql('CREATE TABLE condiment (id INTEGER PRIMARY KEY AUTOINCREMENT NOT NULL, name VARCHAR(255) NOT NULL, explanation VARCHAR(255) NOT NULL, composition VARCHAR(255) DEFAULT NULL, use VARCHAR(255) NOT NULL, note VARCHAR(255) DEFAULT NULL)');
        $this->addSql('CREATE TABLE condiment_substitute (id INTEGER PRIMARY KEY AUTOINCREMENT NOT NULL, name VARCHAR(255) NOT NULL, quantity DOUBLE PRECISION NOT NULL, unit VARCHAR(255) NOT NULL, url VARCHAR(255) DEFAULT NULL, condiment_substitute_group_id INTEGER NOT NULL, CONSTRAINT FK_1F72AB0A3BEA9C2F FOREIGN KEY (condiment_substitute_group_id) REFERENCES condiment_substitute_group (id) NOT DEFERRABLE INITIALLY IMMEDIATE)');
        $this->addSql('CREATE INDEX IDX_1F72AB0A3BEA9C2F ON condiment_substitute (condiment_substitute_group_id)');
        $this->addSql('CREATE TABLE condiment_substitute_group (id INTEGER PRIMARY KEY AUTOINCREMENT NOT NULL, label VARCHAR(255) NOT NULL, condiment_id INTEGER NOT NULL, CONSTRAINT FK_97D8A9E2F37149B FOREIGN KEY (condiment_id) REFERENCES condiment (id) NOT DEFERRABLE INITIALLY IMMEDIATE)');
        $this->addSql('CREATE INDEX IDX_97D8A9E2F37149B ON condiment_substitute_group (condiment_id)');
        $this->addSql('CREATE TABLE favorite (id INTEGER PRIMARY KEY AUTOINCREMENT NOT NULL, saved_at DATETIME NOT NULL, recipe_id INTEGER NOT NULL, CONSTRAINT FK_68C58ED959D8A214 FOREIGN KEY (recipe_id) REFERENCES recipe (id) NOT DEFERRABLE INITIALLY IMMEDIATE)');
        $this->addSql('CREATE INDEX IDX_68C58ED959D8A214 ON favorite (recipe_id)');
        $this->addSql('CREATE TABLE ingredient (id INTEGER PRIMARY KEY AUTOINCREMENT NOT NULL, name VARCHAR(255) NOT NULL)');
        $this->addSql('CREATE TABLE media (id INTEGER PRIMARY KEY AUTOINCREMENT NOT NULL, name VARCHAR(255) NOT NULL, url VARCHAR(255) NOT NULL)');
        $this->addSql('CREATE TABLE recipe (id INTEGER PRIMARY KEY AUTOINCREMENT NOT NULL, name VARCHAR(255) NOT NULL, step VARCHAR(255) NOT NULL, season VARCHAR(255) NOT NULL, time VARCHAR(255) NOT NULL, level VARCHAR(255) NOT NULL)');
        $this->addSql('CREATE TABLE recipe_condiment (id INTEGER PRIMARY KEY AUTOINCREMENT NOT NULL, quantity DOUBLE PRECISION NOT NULL, unit VARCHAR(255) NOT NULL, recipe_id INTEGER NOT NULL, condiment_id INTEGER NOT NULL, CONSTRAINT FK_6A658FEF59D8A214 FOREIGN KEY (recipe_id) REFERENCES recipe (id) NOT DEFERRABLE INITIALLY IMMEDIATE, CONSTRAINT FK_6A658FEFF37149B FOREIGN KEY (condiment_id) REFERENCES condiment (id) NOT DEFERRABLE INITIALLY IMMEDIATE)');
        $this->addSql('CREATE INDEX IDX_6A658FEF59D8A214 ON recipe_condiment (recipe_id)');
        $this->addSql('CREATE INDEX IDX_6A658FEFF37149B ON recipe_condiment (condiment_id)');
        $this->addSql('CREATE TABLE recipe_ingredient (id INTEGER PRIMARY KEY AUTOINCREMENT NOT NULL, quantity DOUBLE PRECISION NOT NULL, unit VARCHAR(50) NOT NULL, recipe_id INTEGER NOT NULL, ingredient_id INTEGER NOT NULL, CONSTRAINT FK_22D1FE1359D8A214 FOREIGN KEY (recipe_id) REFERENCES recipe (id) NOT DEFERRABLE INITIALLY IMMEDIATE, CONSTRAINT FK_22D1FE13933FE08C FOREIGN KEY (ingredient_id) REFERENCES ingredient (id) NOT DEFERRABLE INITIALLY IMMEDIATE)');
        $this->addSql('CREATE INDEX IDX_22D1FE1359D8A214 ON recipe_ingredient (recipe_id)');
        $this->addSql('CREATE INDEX IDX_22D1FE13933FE08C ON recipe_ingredient (ingredient_id)');
        $this->addSql('CREATE TABLE messenger_messages (id INTEGER PRIMARY KEY AUTOINCREMENT NOT NULL, body CLOB NOT NULL, headers CLOB NOT NULL, queue_name VARCHAR(190) NOT NULL, created_at DATETIME NOT NULL, available_at DATETIME NOT NULL, delivered_at DATETIME DEFAULT NULL)');
        $this->addSql('CREATE INDEX IDX_75EA56E0FB7336F0E3BD61CE16BA31DBBF396750 ON messenger_messages (queue_name, available_at, delivered_at, id)');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('DROP TABLE asian_shop');
        $this->addSql('DROP TABLE condiment');
        $this->addSql('DROP TABLE condiment_substitute');
        $this->addSql('DROP TABLE condiment_substitute_group');
        $this->addSql('DROP TABLE favorite');
        $this->addSql('DROP TABLE ingredient');
        $this->addSql('DROP TABLE media');
        $this->addSql('DROP TABLE recipe');
        $this->addSql('DROP TABLE recipe_condiment');
        $this->addSql('DROP TABLE recipe_ingredient');
        $this->addSql('DROP TABLE messenger_messages');
    }
}
