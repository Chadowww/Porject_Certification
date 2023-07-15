<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20230714202951 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE TABLE favorite (user_id INT NOT NULL, book_id INT NOT NULL, INDEX IDX_68C58ED9A76ED395 (user_id), INDEX IDX_68C58ED916A2B381 (book_id), PRIMARY KEY(user_id, book_id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('ALTER TABLE favorite ADD CONSTRAINT FK_68C58ED9A76ED395 FOREIGN KEY (user_id) REFERENCES user (id) ON DELETE CASCADE');
        $this->addSql('ALTER TABLE favorite ADD CONSTRAINT FK_68C58ED916A2B381 FOREIGN KEY (book_id) REFERENCES book (id) ON DELETE CASCADE');
        $this->addSql('ALTER TABLE user_book DROP FOREIGN KEY FK_B164EFF816A2B381');
        $this->addSql('ALTER TABLE user_book DROP FOREIGN KEY FK_B164EFF8A76ED395');
        $this->addSql('DROP TABLE user_book');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE TABLE user_book (user_id INT NOT NULL, book_id INT NOT NULL, INDEX IDX_B164EFF8A76ED395 (user_id), INDEX IDX_B164EFF816A2B381 (book_id), PRIMARY KEY(user_id, book_id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB COMMENT = \'\' ');
        $this->addSql('ALTER TABLE user_book ADD CONSTRAINT FK_B164EFF816A2B381 FOREIGN KEY (book_id) REFERENCES book (id) ON UPDATE NO ACTION ON DELETE CASCADE');
        $this->addSql('ALTER TABLE user_book ADD CONSTRAINT FK_B164EFF8A76ED395 FOREIGN KEY (user_id) REFERENCES user (id) ON UPDATE NO ACTION ON DELETE CASCADE');
        $this->addSql('ALTER TABLE favorite DROP FOREIGN KEY FK_68C58ED9A76ED395');
        $this->addSql('ALTER TABLE favorite DROP FOREIGN KEY FK_68C58ED916A2B381');
        $this->addSql('DROP TABLE favorite');
    }
}
