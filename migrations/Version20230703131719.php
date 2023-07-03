<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20230703131719 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE FULLTEXT INDEX IDX_BDAFD8C85E237E06 ON author (name)');
        $this->addSql('CREATE FULLTEXT INDEX IDX_64C19C15E237E06 ON category (name)');
        $this->addSql('CREATE FULLTEXT INDEX IDX_CCF1F1BA5E237E06 ON editor (name)');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('DROP INDEX IDX_BDAFD8C85E237E06 ON author');
        $this->addSql('DROP INDEX IDX_CCF1F1BA5E237E06 ON editor');
        $this->addSql('DROP INDEX IDX_64C19C15E237E06 ON category');
    }
}
