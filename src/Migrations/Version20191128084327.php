<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20191128084327 extends AbstractMigration
{
    public function getDescription() : string
    {
        return '';
    }

    public function up(Schema $schema) : void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() !== 'mysql', 'Migration can only be executed safely on \'mysql\'.');

        $this->addSql('ALTER TABLE instructor CHANGE per_id per_id INT DEFAULT NULL');
        $this->addSql('ALTER TABLE lesson CHANGE training_id training_id INT DEFAULT NULL, CHANGE instructor_id instructor_id INT DEFAULT NULL');
        $this->addSql('ALTER TABLE member CHANGE person_id person_id INT DEFAULT NULL');
        $this->addSql('ALTER TABLE registration ADD lesson_id INT DEFAULT NULL, CHANGE member_id member_id INT DEFAULT NULL, CHANGE payment payment TINYINT(1) DEFAULT NULL');
        $this->addSql('ALTER TABLE registration ADD CONSTRAINT FK_62A8A7A7CDF80196 FOREIGN KEY (lesson_id) REFERENCES lesson (id)');
        $this->addSql('CREATE INDEX IDX_62A8A7A7CDF80196 ON registration (lesson_id)');
        $this->addSql('ALTER TABLE training CHANGE costs costs DOUBLE PRECISION DEFAULT NULL');
    }

    public function down(Schema $schema) : void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() !== 'mysql', 'Migration can only be executed safely on \'mysql\'.');

        $this->addSql('ALTER TABLE instructor CHANGE per_id per_id INT DEFAULT NULL');
        $this->addSql('ALTER TABLE lesson CHANGE training_id training_id INT DEFAULT NULL, CHANGE instructor_id instructor_id INT DEFAULT NULL');
        $this->addSql('ALTER TABLE member CHANGE person_id person_id INT DEFAULT NULL');
        $this->addSql('ALTER TABLE registration DROP FOREIGN KEY FK_62A8A7A7CDF80196');
        $this->addSql('DROP INDEX IDX_62A8A7A7CDF80196 ON registration');
        $this->addSql('ALTER TABLE registration DROP lesson_id, CHANGE member_id member_id INT DEFAULT NULL, CHANGE payment payment TINYINT(1) DEFAULT \'NULL\'');
        $this->addSql('ALTER TABLE training CHANGE costs costs DOUBLE PRECISION DEFAULT \'NULL\'');
    }
}
