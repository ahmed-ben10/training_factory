<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20191209153107 extends AbstractMigration
{
    public function getDescription() : string
    {
        return '';
    }

    public function up(Schema $schema) : void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() !== 'mysql', 'Migration can only be executed safely on \'mysql\'.');

        $this->addSql('ALTER TABLE instructor CHANGE person_id person_id INT DEFAULT NULL');
        $this->addSql('ALTER TABLE lesson CHANGE training_id training_id INT DEFAULT NULL, CHANGE instructor_id instructor_id INT DEFAULT NULL');
        $this->addSql('ALTER TABLE member CHANGE person_id person_id INT DEFAULT NULL');
        $this->addSql('ALTER TABLE person ADD roles LONGTEXT NOT NULL COMMENT \'(DC2Type:json)\', DROP person_type, CHANGE preprovision preprovision VARCHAR(255) DEFAULT NULL');
        $this->addSql('ALTER TABLE registration CHANGE member_id member_id INT DEFAULT NULL, CHANGE lesson_id lesson_id INT DEFAULT NULL, CHANGE payment payment TINYINT(1) DEFAULT NULL');
        $this->addSql('ALTER TABLE training CHANGE costs costs DOUBLE PRECISION DEFAULT NULL');
    }

    public function down(Schema $schema) : void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() !== 'mysql', 'Migration can only be executed safely on \'mysql\'.');

        $this->addSql('ALTER TABLE instructor CHANGE person_id person_id INT DEFAULT NULL');
        $this->addSql('ALTER TABLE lesson CHANGE training_id training_id INT DEFAULT NULL, CHANGE instructor_id instructor_id INT DEFAULT NULL');
        $this->addSql('ALTER TABLE member CHANGE person_id person_id INT DEFAULT NULL');
        $this->addSql('ALTER TABLE person ADD person_type VARCHAR(255) NOT NULL COLLATE utf8mb4_unicode_ci, DROP roles, CHANGE preprovision preprovision VARCHAR(255) DEFAULT \'NULL\' COLLATE utf8mb4_unicode_ci');
        $this->addSql('ALTER TABLE registration CHANGE member_id member_id INT DEFAULT NULL, CHANGE lesson_id lesson_id INT DEFAULT NULL, CHANGE payment payment TINYINT(1) DEFAULT \'NULL\'');
        $this->addSql('ALTER TABLE training CHANGE costs costs DOUBLE PRECISION DEFAULT \'NULL\'');
    }
}
