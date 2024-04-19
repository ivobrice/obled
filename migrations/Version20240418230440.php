<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20240418230440 extends AbstractMigration
{
    public function getDescription(): string
    {
        return 'Create donnée trajet';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE TABLE trajet (id INT AUTO_INCREMENT NOT NULL, user_id INT DEFAULT NULL, ville_dept VARCHAR(100) DEFAULT NULL, ville_arrv VARCHAR(100) DEFAULT NULL, pays_dept VARCHAR(50) DEFAULT NULL, pays_arrv VARCHAR(50) DEFAULT NULL, date_dept DATETIME DEFAULT NULL COMMENT \'(DC2Type:datetime_immutable)\', nbr_de_place SMALLINT DEFAULT NULL, prix_place NUMERIC(9, 2) DEFAULT NULL, rendez_vs_dept LONGTEXT DEFAULT NULL, rendez_vs_arrv VARCHAR(120) DEFAULT NULL, description LONGTEXT DEFAULT NULL, restrictions LONGTEXT DEFAULT NULL, marq_voiture VARCHAR(25) DEFAULT NULL, nbre_place_arr SMALLINT DEFAULT NULL, prenom VARCHAR(100) DEFAULT NULL, email VARCHAR(180) DEFAULT NULL, phone VARCHAR(30) DEFAULT NULL, annee_naiss DATE DEFAULT NULL, publish TINYINT(1) DEFAULT NULL, created_at DATETIME DEFAULT CURRENT_TIMESTAMP NOT NULL COMMENT \'(DC2Type:datetime_immutable)\', updated_at DATETIME DEFAULT CURRENT_TIMESTAMP COMMENT \'(DC2Type:datetime_immutable)\', INDEX IDX_2B5BA98CA76ED395 (user_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('ALTER TABLE trajet ADD CONSTRAINT FK_2B5BA98CA76ED395 FOREIGN KEY (user_id) REFERENCES `user` (id)');
        $this->addSql('ALTER TABLE user CHANGE created_at created_at DATETIME DEFAULT CURRENT_TIMESTAMP NOT NULL COMMENT \'(DC2Type:datetime_immutable)\', CHANGE updated_at updated_at DATETIME DEFAULT CURRENT_TIMESTAMP COMMENT \'(DC2Type:datetime_immutable)\'');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE trajet DROP FOREIGN KEY FK_2B5BA98CA76ED395');
        $this->addSql('DROP TABLE trajet');
        $this->addSql('ALTER TABLE `user` CHANGE created_at created_at DATETIME NOT NULL COMMENT \'(DC2Type:datetime_immutable)\', CHANGE updated_at updated_at DATETIME DEFAULT NULL COMMENT \'(DC2Type:datetime_immutable)\'');
    }
}
