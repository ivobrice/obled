<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20240421231135 extends AbstractMigration
{
    public function getDescription(): string
    {
        return 'Create donnée Reservation';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE TABLE reservation (id INT AUTO_INCREMENT NOT NULL, user_id INT DEFAULT NULL, trajet_id INT NOT NULL, phone_passager VARCHAR(30) DEFAULT NULL, nbr_de_place_rsrv SMALLINT DEFAULT NULL, prix_place_rsrv NUMERIC(9, 2) DEFAULT NULL, ville_dept VARCHAR(100) DEFAULT NULL, ville_arrv VARCHAR(100) DEFAULT NULL, date_dept DATETIME DEFAULT NULL, mail_passager VARCHAR(180) DEFAULT NULL, mail_chauf VARCHAR(180) DEFAULT NULL, phone_chauf VARCHAR(30) DEFAULT NULL, date_validation_client DATETIME DEFAULT NULL COMMENT \'(DC2Type:datetime_immutable)\', paiement TINYINT(1) DEFAULT NULL, created_at DATETIME DEFAULT CURRENT_TIMESTAMP NOT NULL COMMENT \'(DC2Type:datetime_immutable)\', updated_at DATETIME DEFAULT CURRENT_TIMESTAMP COMMENT \'(DC2Type:datetime_immutable)\', publish TINYINT(1) DEFAULT NULL, code_user VARCHAR(20) DEFAULT NULL, hashed_code VARCHAR(255) DEFAULT NULL, hashed_code2 VARCHAR(45) DEFAULT NULL, INDEX IDX_42C84955A76ED395 (user_id), INDEX IDX_42C84955D12A823 (trajet_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('ALTER TABLE reservation ADD CONSTRAINT FK_42C84955A76ED395 FOREIGN KEY (user_id) REFERENCES `user` (id)');
        $this->addSql('ALTER TABLE reservation ADD CONSTRAINT FK_42C84955D12A823 FOREIGN KEY (trajet_id) REFERENCES trajet (id)');
        $this->addSql('ALTER TABLE trajet ADD code_user VARCHAR(20) DEFAULT NULL, ADD hashed_code VARCHAR(255) DEFAULT NULL, ADD hashed_code2 VARCHAR(45) DEFAULT NULL');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE reservation DROP FOREIGN KEY FK_42C84955A76ED395');
        $this->addSql('ALTER TABLE reservation DROP FOREIGN KEY FK_42C84955D12A823');
        $this->addSql('DROP TABLE reservation');
        $this->addSql('ALTER TABLE trajet DROP code_user, DROP hashed_code, DROP hashed_code2');
    }
}
