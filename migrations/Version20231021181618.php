<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20231021181618 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE classroom ADD ref INT AUTO_INCREMENT NOT NULL, DROP id, ADD PRIMARY KEY (ref)');
        $this->addSql('ALTER TABLE student ADD nc_id INT DEFAULT NULL, CHANGE age age INT NOT NULL');
        $this->addSql('ALTER TABLE student ADD CONSTRAINT FK_B723AF3350B37AFE FOREIGN KEY (nc_id) REFERENCES classroom (ref)');
        $this->addSql('CREATE INDEX IDX_B723AF3350B37AFE ON student (nc_id)');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE classroom MODIFY ref INT NOT NULL');
        $this->addSql('DROP INDEX `primary` ON classroom');
        $this->addSql('ALTER TABLE classroom ADD id INT NOT NULL, DROP ref');
        $this->addSql('ALTER TABLE student DROP FOREIGN KEY FK_B723AF3350B37AFE');
        $this->addSql('DROP INDEX IDX_B723AF3350B37AFE ON student');
        $this->addSql('ALTER TABLE student DROP nc_id, CHANGE age age VARCHAR(255) NOT NULL');
    }
}
