<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

final class Version20231121000255 extends AbstractMigration
{
    public function getDescription(): string
    {
        return 'Created tables for users and job applications';
    }

    public function up(Schema $schema): void
    {
        $this->addSql('CREATE SEQUENCE job_application_id_seq INCREMENT BY 1 MINVALUE 1 START 1');
        $this->addSql('CREATE SEQUENCE app_user_id_seq INCREMENT BY 1 MINVALUE 1 START 1');
        $this->addSql('CREATE TABLE job_application (id INT NOT NULL, first_name VARCHAR(64) NOT NULL, last_name VARCHAR(64) NOT NULL, email VARCHAR(255) NOT NULL, phone_number VARCHAR(12) NOT NULL, expected_salary INT NOT NULL, estimated_experience_level INT NOT NULL, first_display_date_time TIMESTAMP(0) WITHOUT TIME ZONE DEFAULT NULL, created TIMESTAMP(0) WITHOUT TIME ZONE NOT NULL, PRIMARY KEY(id))');
        $this->addSql('COMMENT ON COLUMN job_application.first_display_date_time IS \'(DC2Type:datetime_immutable)\'');
        $this->addSql('COMMENT ON COLUMN job_application.created IS \'(DC2Type:datetime_immutable)\'');
        $this->addSql('CREATE TABLE app_user (id INT NOT NULL, username VARCHAR(64) NOT NULL, password VARCHAR(255) NOT NULL, PRIMARY KEY(id))');
        $this->addSql('CREATE UNIQUE INDEX UNIQ_8D93D649F85E0677 ON app_user (username)');
    }

    public function down(Schema $schema): void
    {
        $this->addSql('CREATE SCHEMA public');
        $this->addSql('DROP SEQUENCE job_application_id_seq CASCADE');
        $this->addSql('DROP SEQUENCE app_user_id_seq CASCADE');
        $this->addSql('DROP TABLE job_application');
        $this->addSql('DROP TABLE app_user');
    }
}
