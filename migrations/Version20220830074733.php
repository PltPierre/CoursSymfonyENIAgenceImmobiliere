<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20220830074733 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE TABLE bien_acheteur (bien_id INT NOT NULL, acheteur_id INT NOT NULL, INDEX IDX_C20EDFE4BD95B80F (bien_id), INDEX IDX_C20EDFE496A7BB5F (acheteur_id), PRIMARY KEY(bien_id, acheteur_id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('ALTER TABLE bien_acheteur ADD CONSTRAINT FK_C20EDFE4BD95B80F FOREIGN KEY (bien_id) REFERENCES bien (id) ON DELETE CASCADE');
        $this->addSql('ALTER TABLE bien_acheteur ADD CONSTRAINT FK_C20EDFE496A7BB5F FOREIGN KEY (acheteur_id) REFERENCES acheteur (id) ON DELETE CASCADE');
        $this->addSql('ALTER TABLE acheteur_bien DROP FOREIGN KEY FK_4D106828BD95B80F');
        $this->addSql('ALTER TABLE acheteur_bien DROP FOREIGN KEY FK_4D10682896A7BB5F');
        $this->addSql('DROP TABLE acheteur_bien');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE TABLE acheteur_bien (acheteur_id INT NOT NULL, bien_id INT NOT NULL, INDEX IDX_4D10682896A7BB5F (acheteur_id), INDEX IDX_4D106828BD95B80F (bien_id), PRIMARY KEY(acheteur_id, bien_id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB COMMENT = \'\' ');
        $this->addSql('ALTER TABLE acheteur_bien ADD CONSTRAINT FK_4D106828BD95B80F FOREIGN KEY (bien_id) REFERENCES bien (id) ON DELETE CASCADE');
        $this->addSql('ALTER TABLE acheteur_bien ADD CONSTRAINT FK_4D10682896A7BB5F FOREIGN KEY (acheteur_id) REFERENCES acheteur (id) ON DELETE CASCADE');
        $this->addSql('ALTER TABLE bien_acheteur DROP FOREIGN KEY FK_C20EDFE4BD95B80F');
        $this->addSql('ALTER TABLE bien_acheteur DROP FOREIGN KEY FK_C20EDFE496A7BB5F');
        $this->addSql('DROP TABLE bien_acheteur');
    }
}
