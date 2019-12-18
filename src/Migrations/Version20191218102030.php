<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20191218102030 extends AbstractMigration
{
    public function getDescription() : string
    {
        return '';
    }

    public function up(Schema $schema) : void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() !== 'mysql', 'Migration can only be executed safely on \'mysql\'.');

        $this->addSql('CREATE TABLE instructor (id INT AUTO_INCREMENT NOT NULL, person_id INT DEFAULT NULL, hiring_date DATETIME NOT NULL, salary DOUBLE PRECISION NOT NULL, INDEX IDX_31FC43DD217BBB47 (person_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci ENGINE = InnoDB');
        $this->addSql('CREATE TABLE lesson (id INT AUTO_INCREMENT NOT NULL, training_id INT DEFAULT NULL, instructor_id INT DEFAULT NULL, time TIME NOT NULL, date DATETIME NOT NULL, location VARCHAR(255) NOT NULL, max_persons INT NOT NULL, INDEX IDX_F87474F3BEFD98D1 (training_id), INDEX IDX_F87474F38C4FC193 (instructor_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci ENGINE = InnoDB');
        $this->addSql('CREATE TABLE member (id INT AUTO_INCREMENT NOT NULL, person_id INT DEFAULT NULL, street VARCHAR(255) NOT NULL, postal_code VARCHAR(255) NOT NULL, place VARCHAR(255) NOT NULL, INDEX IDX_70E4FA78217BBB47 (person_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci ENGINE = InnoDB');
        $this->addSql('CREATE TABLE person (id INT AUTO_INCREMENT NOT NULL, loginname VARCHAR(255) NOT NULL, password VARCHAR(255) NOT NULL, firstname VARCHAR(255) NOT NULL, preprovision VARCHAR(255) DEFAULT NULL, lastname VARCHAR(255) NOT NULL, dateofbirth DATETIME NOT NULL, gender VARCHAR(10) NOT NULL, emailaddress VARCHAR(255) NOT NULL, roles LONGTEXT NOT NULL COMMENT \'(DC2Type:json)\', UNIQUE INDEX UNIQ_34DCD1766D1A90C6 (loginname), UNIQUE INDEX UNIQ_34DCD17689E75BF2 (emailaddress), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci ENGINE = InnoDB');
        $this->addSql('CREATE TABLE registration (id INT AUTO_INCREMENT NOT NULL, member_id INT DEFAULT NULL, lesson_id INT DEFAULT NULL, payment TINYINT(1) DEFAULT NULL, INDEX IDX_62A8A7A77597D3FE (member_id), INDEX IDX_62A8A7A7CDF80196 (lesson_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci ENGINE = InnoDB');
        $this->addSql('CREATE TABLE training (id INT AUTO_INCREMENT NOT NULL, naam VARCHAR(255) NOT NULL, description LONGTEXT NOT NULL, duration TIME NOT NULL, costs DOUBLE PRECISION DEFAULT NULL, image_dir VARCHAR(255) NOT NULL, PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci ENGINE = InnoDB');
        $this->addSql('ALTER TABLE instructor ADD CONSTRAINT FK_31FC43DD217BBB47 FOREIGN KEY (person_id) REFERENCES person (id)');
        $this->addSql('ALTER TABLE lesson ADD CONSTRAINT FK_F87474F3BEFD98D1 FOREIGN KEY (training_id) REFERENCES training (id)');
        $this->addSql('ALTER TABLE lesson ADD CONSTRAINT FK_F87474F38C4FC193 FOREIGN KEY (instructor_id) REFERENCES instructor (id)');
        $this->addSql('ALTER TABLE member ADD CONSTRAINT FK_70E4FA78217BBB47 FOREIGN KEY (person_id) REFERENCES person (id)');
        $this->addSql('ALTER TABLE registration ADD CONSTRAINT FK_62A8A7A77597D3FE FOREIGN KEY (member_id) REFERENCES member (id)');
        $this->addSql('ALTER TABLE registration ADD CONSTRAINT FK_62A8A7A7CDF80196 FOREIGN KEY (lesson_id) REFERENCES lesson (id)');
    }

    public function down(Schema $schema) : void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() !== 'mysql', 'Migration can only be executed safely on \'mysql\'.');

        $this->addSql('ALTER TABLE lesson DROP FOREIGN KEY FK_F87474F38C4FC193');
        $this->addSql('ALTER TABLE registration DROP FOREIGN KEY FK_62A8A7A7CDF80196');
        $this->addSql('ALTER TABLE registration DROP FOREIGN KEY FK_62A8A7A77597D3FE');
        $this->addSql('ALTER TABLE instructor DROP FOREIGN KEY FK_31FC43DD217BBB47');
        $this->addSql('ALTER TABLE member DROP FOREIGN KEY FK_70E4FA78217BBB47');
        $this->addSql('ALTER TABLE lesson DROP FOREIGN KEY FK_F87474F3BEFD98D1');
        $this->addSql('DROP TABLE instructor');
        $this->addSql('DROP TABLE lesson');
        $this->addSql('DROP TABLE member');
        $this->addSql('DROP TABLE person');
        $this->addSql('DROP TABLE registration');
        $this->addSql('DROP TABLE training');
    }
}
