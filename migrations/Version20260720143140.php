<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20260720143140 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE favorite ADD COLUMN person INTEGER NOT NULL');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE TEMPORARY TABLE __temp__favorite AS SELECT id, saved_at, recipe_id, user_id FROM favorite');
        $this->addSql('DROP TABLE favorite');
        $this->addSql('CREATE TABLE favorite (id INTEGER PRIMARY KEY AUTOINCREMENT NOT NULL, saved_at DATETIME NOT NULL, recipe_id INTEGER NOT NULL, user_id INTEGER NOT NULL, CONSTRAINT FK_68C58ED959D8A214 FOREIGN KEY (recipe_id) REFERENCES recipe (id) NOT DEFERRABLE INITIALLY IMMEDIATE, CONSTRAINT FK_68C58ED9A76ED395 FOREIGN KEY (user_id) REFERENCES user (id) NOT DEFERRABLE INITIALLY IMMEDIATE)');
        $this->addSql('INSERT INTO favorite (id, saved_at, recipe_id, user_id) SELECT id, saved_at, recipe_id, user_id FROM __temp__favorite');
        $this->addSql('DROP TABLE __temp__favorite');
        $this->addSql('CREATE INDEX IDX_68C58ED959D8A214 ON favorite (recipe_id)');
        $this->addSql('CREATE INDEX IDX_68C58ED9A76ED395 ON favorite (user_id)');
    }
}
