<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20240509164703 extends AbstractMigration
{
    public function getDescription(): string
    {
        return 'Create données user, trajet, reservation';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE TABLE reservation (id INT AUTO_INCREMENT NOT NULL, user_id INT DEFAULT NULL, trajet_id INT NOT NULL, phone_passager VARCHAR(30) DEFAULT NULL, nbr_de_place_rsrv SMALLINT DEFAULT NULL, prix_place_rsrv INT DEFAULT NULL, ville_dept VARCHAR(100) DEFAULT NULL, ville_arrv VARCHAR(100) DEFAULT NULL, date_dept DATETIME DEFAULT NULL, mail_passager VARCHAR(180) DEFAULT NULL, mail_chauf VARCHAR(180) DEFAULT NULL, phone_chauf VARCHAR(30) DEFAULT NULL, date_validation_client DATETIME DEFAULT NULL COMMENT \'(DC2Type:datetime_immutable)\', paiement TINYINT(1) DEFAULT NULL, created_at DATETIME DEFAULT CURRENT_TIMESTAMP NOT NULL COMMENT \'(DC2Type:datetime_immutable)\', updated_at DATETIME DEFAULT CURRENT_TIMESTAMP COMMENT \'(DC2Type:datetime_immutable)\', publish TINYINT(1) DEFAULT NULL, code_user VARCHAR(20) DEFAULT NULL, hashed_code VARCHAR(255) DEFAULT NULL, hashed_code2 VARCHAR(45) DEFAULT NULL, INDEX IDX_42C84955A76ED395 (user_id), INDEX IDX_42C84955D12A823 (trajet_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE trajet (id INT AUTO_INCREMENT NOT NULL, user_id INT DEFAULT NULL, ville_dept VARCHAR(100) DEFAULT NULL, ville_arrv VARCHAR(100) DEFAULT NULL, pays_dept VARCHAR(50) DEFAULT NULL, pays_arrv VARCHAR(50) DEFAULT NULL, date_dept DATETIME DEFAULT NULL COMMENT \'(DC2Type:datetime_immutable)\', nbr_de_place SMALLINT DEFAULT NULL, prix_place INT DEFAULT NULL, rendez_vs_dept LONGTEXT DEFAULT NULL, rendez_vs_arrv VARCHAR(120) DEFAULT NULL, description LONGTEXT DEFAULT NULL, restrictions LONGTEXT DEFAULT NULL, marq_voiture VARCHAR(25) DEFAULT NULL, nbre_place_arr SMALLINT DEFAULT NULL, prenom VARCHAR(100) DEFAULT NULL, email VARCHAR(180) NOT NULL, phone VARCHAR(30) DEFAULT NULL, annee_naiss DATE DEFAULT NULL, created_at DATETIME DEFAULT CURRENT_TIMESTAMP NOT NULL COMMENT \'(DC2Type:datetime_immutable)\', updated_at DATETIME DEFAULT CURRENT_TIMESTAMP COMMENT \'(DC2Type:datetime_immutable)\', publish TINYINT(1) DEFAULT NULL, code_user VARCHAR(20) DEFAULT NULL, hashed_code VARCHAR(255) DEFAULT NULL, hashed_code2 VARCHAR(45) DEFAULT NULL, INDEX IDX_2B5BA98CA76ED395 (user_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE `user` (id INT AUTO_INCREMENT NOT NULL, email VARCHAR(180) NOT NULL, roles JSON NOT NULL COMMENT \'(DC2Type:json)\', password VARCHAR(255) NOT NULL, prenom VARCHAR(100) DEFAULT NULL, annee_naiss DATE DEFAULT NULL, phone VARCHAR(30) DEFAULT NULL, created_at DATETIME DEFAULT CURRENT_TIMESTAMP NOT NULL COMMENT \'(DC2Type:datetime_immutable)\', updated_at DATETIME DEFAULT CURRENT_TIMESTAMP COMMENT \'(DC2Type:datetime_immutable)\', UNIQUE INDEX UNIQ_IDENTIFIER_EMAIL (email), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('ALTER TABLE reservation ADD CONSTRAINT FK_42C84955A76ED395 FOREIGN KEY (user_id) REFERENCES `user` (id)');
        $this->addSql('ALTER TABLE reservation ADD CONSTRAINT FK_42C84955D12A823 FOREIGN KEY (trajet_id) REFERENCES trajet (id)');
        $this->addSql('ALTER TABLE trajet ADD CONSTRAINT FK_2B5BA98CA76ED395 FOREIGN KEY (user_id) REFERENCES `user` (id)');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE reservation DROP FOREIGN KEY FK_42C84955A76ED395');
        $this->addSql('ALTER TABLE reservation DROP FOREIGN KEY FK_42C84955D12A823');
        $this->addSql('ALTER TABLE trajet DROP FOREIGN KEY FK_2B5BA98CA76ED395');
        $this->addSql('DROP TABLE reservation');
        $this->addSql('DROP TABLE trajet');
        $this->addSql('DROP TABLE `user`');
    }
}
