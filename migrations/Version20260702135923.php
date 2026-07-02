<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20260702135923 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE TABLE recipe_shopping_list (id INTEGER PRIMARY KEY AUTOINCREMENT NOT NULL, person INTEGER NOT NULL, recipe_id INTEGER NOT NULL, shopping_list_id INTEGER NOT NULL, CONSTRAINT FK_AD34B29F59D8A214 FOREIGN KEY (recipe_id) REFERENCES recipe (id) NOT DEFERRABLE INITIALLY IMMEDIATE, CONSTRAINT FK_AD34B29F23245BF9 FOREIGN KEY (shopping_list_id) REFERENCES shopping_list (id) NOT DEFERRABLE INITIALLY IMMEDIATE)');
        $this->addSql('CREATE INDEX IDX_AD34B29F59D8A214 ON recipe_shopping_list (recipe_id)');
        $this->addSql('CREATE INDEX IDX_AD34B29F23245BF9 ON recipe_shopping_list (shopping_list_id)');
        $this->addSql('CREATE TABLE shopping_list (id INTEGER PRIMARY KEY AUTOINCREMENT NOT NULL, memo VARCHAR(255) DEFAULT NULL, created_at DATETIME NOT NULL)');
        $this->addSql('CREATE TABLE shopping_list_substitute (id INTEGER PRIMARY KEY AUTOINCREMENT NOT NULL, recipe_id INTEGER NOT NULL, condiment_substitute_group_id INTEGER NOT NULL, condiment_id INTEGER NOT NULL, shopping_list_id INTEGER NOT NULL, CONSTRAINT FK_BE8CBB6C59D8A214 FOREIGN KEY (recipe_id) REFERENCES recipe (id) NOT DEFERRABLE INITIALLY IMMEDIATE, CONSTRAINT FK_BE8CBB6C3BEA9C2F FOREIGN KEY (condiment_substitute_group_id) REFERENCES condiment_substitute_group (id) NOT DEFERRABLE INITIALLY IMMEDIATE, CONSTRAINT FK_BE8CBB6CF37149B FOREIGN KEY (condiment_id) REFERENCES condiment (id) NOT DEFERRABLE INITIALLY IMMEDIATE, CONSTRAINT FK_BE8CBB6C23245BF9 FOREIGN KEY (shopping_list_id) REFERENCES shopping_list (id) NOT DEFERRABLE INITIALLY IMMEDIATE)');
        $this->addSql('CREATE INDEX IDX_BE8CBB6C59D8A214 ON shopping_list_substitute (recipe_id)');
        $this->addSql('CREATE INDEX IDX_BE8CBB6C3BEA9C2F ON shopping_list_substitute (condiment_substitute_group_id)');
        $this->addSql('CREATE INDEX IDX_BE8CBB6CF37149B ON shopping_list_substitute (condiment_id)');
        $this->addSql('CREATE INDEX IDX_BE8CBB6C23245BF9 ON shopping_list_substitute (shopping_list_id)');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('DROP TABLE recipe_shopping_list');
        $this->addSql('DROP TABLE shopping_list');
        $this->addSql('DROP TABLE shopping_list_substitute');
    }
}
