<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20260805221405 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE TABLE asian_shop (id INT AUTO_INCREMENT NOT NULL, name VARCHAR(255) NOT NULL, postcode VARCHAR(255) NOT NULL, address VARCHAR(255) NOT NULL, telephone VARCHAR(255) NOT NULL, explanation_service LONGTEXT DEFAULT NULL, PRIMARY KEY (id)) DEFAULT CHARACTER SET utf8mb4');
        $this->addSql('CREATE TABLE condiment (id INT AUTO_INCREMENT NOT NULL, name VARCHAR(255) NOT NULL, explanation VARCHAR(255) NOT NULL, composition VARCHAR(255) DEFAULT NULL, `use` VARCHAR(255) NOT NULL, note VARCHAR(255) DEFAULT NULL, url VARCHAR(255) DEFAULT NULL, image_url VARCHAR(255) NOT NULL, PRIMARY KEY (id)) DEFAULT CHARACTER SET utf8mb4');
        $this->addSql('CREATE TABLE condiment_substitute (id INT AUTO_INCREMENT NOT NULL, name VARCHAR(255) NOT NULL, quantity DOUBLE PRECISION NOT NULL, unit VARCHAR(255) NOT NULL, url VARCHAR(255) DEFAULT NULL, condiment_substitute_group_id INT NOT NULL, INDEX IDX_1F72AB0A3BEA9C2F (condiment_substitute_group_id), PRIMARY KEY (id)) DEFAULT CHARACTER SET utf8mb4');
        $this->addSql('CREATE TABLE condiment_substitute_group (id INT AUTO_INCREMENT NOT NULL, label VARCHAR(255) NOT NULL, condiment_id INT NOT NULL, INDEX IDX_97D8A9E2F37149B (condiment_id), PRIMARY KEY (id)) DEFAULT CHARACTER SET utf8mb4');
        $this->addSql('CREATE TABLE favorite (id INT AUTO_INCREMENT NOT NULL, saved_at DATETIME NOT NULL, person INT NOT NULL, recipe_id INT NOT NULL, user_id INT NOT NULL, INDEX IDX_68C58ED959D8A214 (recipe_id), INDEX IDX_68C58ED9A76ED395 (user_id), PRIMARY KEY (id)) DEFAULT CHARACTER SET utf8mb4');
        $this->addSql('CREATE TABLE ingredient (id INT AUTO_INCREMENT NOT NULL, name VARCHAR(255) NOT NULL, PRIMARY KEY (id)) DEFAULT CHARACTER SET utf8mb4');
        $this->addSql('CREATE TABLE media (id INT AUTO_INCREMENT NOT NULL, name VARCHAR(255) NOT NULL, url VARCHAR(255) NOT NULL, type VARCHAR(20) DEFAULT \'photo\' NOT NULL, step_order INT DEFAULT NULL, step_description VARCHAR(255) DEFAULT NULL, recipe_id INT NOT NULL, INDEX IDX_6A2CA10C59D8A214 (recipe_id), PRIMARY KEY (id)) DEFAULT CHARACTER SET utf8mb4');
        $this->addSql('CREATE TABLE recipe (id INT AUTO_INCREMENT NOT NULL, name VARCHAR(255) NOT NULL, step LONGTEXT NOT NULL, season VARCHAR(255) NOT NULL, time VARCHAR(255) NOT NULL, level VARCHAR(255) NOT NULL, main_category VARCHAR(255) NOT NULL, description LONGTEXT NOT NULL, PRIMARY KEY (id)) DEFAULT CHARACTER SET utf8mb4');
        $this->addSql('CREATE TABLE recipe_condiment (id INT AUTO_INCREMENT NOT NULL, quantity DOUBLE PRECISION NOT NULL, unit VARCHAR(255) NOT NULL, recipe_id INT NOT NULL, condiment_id INT NOT NULL, INDEX IDX_6A658FEF59D8A214 (recipe_id), INDEX IDX_6A658FEFF37149B (condiment_id), PRIMARY KEY (id)) DEFAULT CHARACTER SET utf8mb4');
        $this->addSql('CREATE TABLE recipe_ingredient (id INT AUTO_INCREMENT NOT NULL, quantity DOUBLE PRECISION NOT NULL, unit VARCHAR(50) NOT NULL, recipe_id INT NOT NULL, ingredient_id INT NOT NULL, INDEX IDX_22D1FE1359D8A214 (recipe_id), INDEX IDX_22D1FE13933FE08C (ingredient_id), PRIMARY KEY (id)) DEFAULT CHARACTER SET utf8mb4');
        $this->addSql('CREATE TABLE recipe_shopping_list (id INT AUTO_INCREMENT NOT NULL, person INT NOT NULL, recipe_id INT NOT NULL, shopping_list_id INT NOT NULL, INDEX IDX_AD34B29F59D8A214 (recipe_id), INDEX IDX_AD34B29F23245BF9 (shopping_list_id), PRIMARY KEY (id)) DEFAULT CHARACTER SET utf8mb4');
        $this->addSql('CREATE TABLE shopping_list (id INT AUTO_INCREMENT NOT NULL, memo VARCHAR(255) DEFAULT NULL, created_at DATETIME NOT NULL, user_id INT NOT NULL, INDEX IDX_3DC1A459A76ED395 (user_id), PRIMARY KEY (id)) DEFAULT CHARACTER SET utf8mb4');
        $this->addSql('CREATE TABLE shopping_list_substitute (id INT AUTO_INCREMENT NOT NULL, recipe_id INT NOT NULL, condiment_substitute_group_id INT NOT NULL, condiment_id INT NOT NULL, shopping_list_id INT NOT NULL, INDEX IDX_BE8CBB6C59D8A214 (recipe_id), INDEX IDX_BE8CBB6C3BEA9C2F (condiment_substitute_group_id), INDEX IDX_BE8CBB6CF37149B (condiment_id), INDEX IDX_BE8CBB6C23245BF9 (shopping_list_id), PRIMARY KEY (id)) DEFAULT CHARACTER SET utf8mb4');
        $this->addSql('CREATE TABLE user (id INT AUTO_INCREMENT NOT NULL, email VARCHAR(180) NOT NULL, role VARCHAR(20) NOT NULL, password VARCHAR(255) NOT NULL, username VARCHAR(255) NOT NULL, UNIQUE INDEX UNIQ_IDENTIFIER_EMAIL (email), PRIMARY KEY (id)) DEFAULT CHARACTER SET utf8mb4');
        $this->addSql('CREATE TABLE messenger_messages (id BIGINT AUTO_INCREMENT NOT NULL, body LONGTEXT NOT NULL, headers LONGTEXT NOT NULL, queue_name VARCHAR(190) NOT NULL, created_at DATETIME NOT NULL, available_at DATETIME NOT NULL, delivered_at DATETIME DEFAULT NULL, INDEX IDX_75EA56E0FB7336F0E3BD61CE16BA31DBBF396750 (queue_name, available_at, delivered_at, id), PRIMARY KEY (id)) DEFAULT CHARACTER SET utf8mb4');
        $this->addSql('ALTER TABLE condiment_substitute ADD CONSTRAINT FK_1F72AB0A3BEA9C2F FOREIGN KEY (condiment_substitute_group_id) REFERENCES condiment_substitute_group (id)');
        $this->addSql('ALTER TABLE condiment_substitute_group ADD CONSTRAINT FK_97D8A9E2F37149B FOREIGN KEY (condiment_id) REFERENCES condiment (id)');
        $this->addSql('ALTER TABLE favorite ADD CONSTRAINT FK_68C58ED959D8A214 FOREIGN KEY (recipe_id) REFERENCES recipe (id)');
        $this->addSql('ALTER TABLE favorite ADD CONSTRAINT FK_68C58ED9A76ED395 FOREIGN KEY (user_id) REFERENCES user (id)');
        $this->addSql('ALTER TABLE media ADD CONSTRAINT FK_6A2CA10C59D8A214 FOREIGN KEY (recipe_id) REFERENCES recipe (id)');
        $this->addSql('ALTER TABLE recipe_condiment ADD CONSTRAINT FK_6A658FEF59D8A214 FOREIGN KEY (recipe_id) REFERENCES recipe (id)');
        $this->addSql('ALTER TABLE recipe_condiment ADD CONSTRAINT FK_6A658FEFF37149B FOREIGN KEY (condiment_id) REFERENCES condiment (id)');
        $this->addSql('ALTER TABLE recipe_ingredient ADD CONSTRAINT FK_22D1FE1359D8A214 FOREIGN KEY (recipe_id) REFERENCES recipe (id)');
        $this->addSql('ALTER TABLE recipe_ingredient ADD CONSTRAINT FK_22D1FE13933FE08C FOREIGN KEY (ingredient_id) REFERENCES ingredient (id)');
        $this->addSql('ALTER TABLE recipe_shopping_list ADD CONSTRAINT FK_AD34B29F59D8A214 FOREIGN KEY (recipe_id) REFERENCES recipe (id)');
        $this->addSql('ALTER TABLE recipe_shopping_list ADD CONSTRAINT FK_AD34B29F23245BF9 FOREIGN KEY (shopping_list_id) REFERENCES shopping_list (id)');
        $this->addSql('ALTER TABLE shopping_list ADD CONSTRAINT FK_3DC1A459A76ED395 FOREIGN KEY (user_id) REFERENCES user (id)');
        $this->addSql('ALTER TABLE shopping_list_substitute ADD CONSTRAINT FK_BE8CBB6C59D8A214 FOREIGN KEY (recipe_id) REFERENCES recipe (id)');
        $this->addSql('ALTER TABLE shopping_list_substitute ADD CONSTRAINT FK_BE8CBB6C3BEA9C2F FOREIGN KEY (condiment_substitute_group_id) REFERENCES condiment_substitute_group (id)');
        $this->addSql('ALTER TABLE shopping_list_substitute ADD CONSTRAINT FK_BE8CBB6CF37149B FOREIGN KEY (condiment_id) REFERENCES condiment (id)');
        $this->addSql('ALTER TABLE shopping_list_substitute ADD CONSTRAINT FK_BE8CBB6C23245BF9 FOREIGN KEY (shopping_list_id) REFERENCES shopping_list (id)');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE condiment_substitute DROP FOREIGN KEY FK_1F72AB0A3BEA9C2F');
        $this->addSql('ALTER TABLE condiment_substitute_group DROP FOREIGN KEY FK_97D8A9E2F37149B');
        $this->addSql('ALTER TABLE favorite DROP FOREIGN KEY FK_68C58ED959D8A214');
        $this->addSql('ALTER TABLE favorite DROP FOREIGN KEY FK_68C58ED9A76ED395');
        $this->addSql('ALTER TABLE media DROP FOREIGN KEY FK_6A2CA10C59D8A214');
        $this->addSql('ALTER TABLE recipe_condiment DROP FOREIGN KEY FK_6A658FEF59D8A214');
        $this->addSql('ALTER TABLE recipe_condiment DROP FOREIGN KEY FK_6A658FEFF37149B');
        $this->addSql('ALTER TABLE recipe_ingredient DROP FOREIGN KEY FK_22D1FE1359D8A214');
        $this->addSql('ALTER TABLE recipe_ingredient DROP FOREIGN KEY FK_22D1FE13933FE08C');
        $this->addSql('ALTER TABLE recipe_shopping_list DROP FOREIGN KEY FK_AD34B29F59D8A214');
        $this->addSql('ALTER TABLE recipe_shopping_list DROP FOREIGN KEY FK_AD34B29F23245BF9');
        $this->addSql('ALTER TABLE shopping_list DROP FOREIGN KEY FK_3DC1A459A76ED395');
        $this->addSql('ALTER TABLE shopping_list_substitute DROP FOREIGN KEY FK_BE8CBB6C59D8A214');
        $this->addSql('ALTER TABLE shopping_list_substitute DROP FOREIGN KEY FK_BE8CBB6C3BEA9C2F');
        $this->addSql('ALTER TABLE shopping_list_substitute DROP FOREIGN KEY FK_BE8CBB6CF37149B');
        $this->addSql('ALTER TABLE shopping_list_substitute DROP FOREIGN KEY FK_BE8CBB6C23245BF9');
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
        $this->addSql('DROP TABLE recipe_shopping_list');
        $this->addSql('DROP TABLE shopping_list');
        $this->addSql('DROP TABLE shopping_list_substitute');
        $this->addSql('DROP TABLE user');
        $this->addSql('DROP TABLE messenger_messages');
    }
}
