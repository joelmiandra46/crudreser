<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20220807152800 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE reservation DROP FOREIGN KEY FK_42C8495576E50838');
        $this->addSql('ALTER TABLE reservation DROP FOREIGN KEY FK_42C8495567F0C0D4');
        $this->addSql('DROP INDEX IDX_42C8495567F0C0D4 ON reservation');
        $this->addSql('DROP INDEX IDX_42C8495576E50838 ON reservation');
        $this->addSql('ALTER TABLE reservation DROP idclient_id, DROP idsalle_id');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE reservation ADD idclient_id INT NOT NULL, ADD idsalle_id INT NOT NULL');
        $this->addSql('ALTER TABLE reservation ADD CONSTRAINT FK_42C8495576E50838 FOREIGN KEY (idsalle_id) REFERENCES salles (id)');
        $this->addSql('ALTER TABLE reservation ADD CONSTRAINT FK_42C8495567F0C0D4 FOREIGN KEY (idclient_id) REFERENCES client (id)');
        $this->addSql('CREATE INDEX IDX_42C8495567F0C0D4 ON reservation (idclient_id)');
        $this->addSql('CREATE INDEX IDX_42C8495576E50838 ON reservation (idsalle_id)');
    }
}
