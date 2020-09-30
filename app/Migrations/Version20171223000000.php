<?php declare(strict_types = 1);

namespace Star\BacklogVelocity\Migrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
class Version20171223000000 extends AbstractMigration
{
    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() !== 'sqlite', 'Migration can only be executed safely on \'sqlite\'.');

        $this->addSql('CREATE TABLE backlog_projects (
            id VARCHAR(255) NOT NULL,
            name VARCHAR(255) NOT NULL,
            PRIMARY KEY(id))'
        );
        $this->addSql('CREATE TABLE backlog_commitments (
            id INTEGER NOT NULL,
            sprint_id VARCHAR(255) NOT NULL,
            man_days INTEGER NOT NULL,
            member_id VARCHAR(255) NOT NULL,
            PRIMARY KEY(id))'
        );
        $this->addSql('CREATE INDEX IDX_4268BB2F8C24077B ON backlog_commitments (sprint_id)');
        $this->addSql('CREATE UNIQUE INDEX unique_commitment_index ON backlog_commitments (sprint_id, member_id)');
        $this->addSql('CREATE TABLE backlog_sprints (
            id VARCHAR(255) NOT NULL,
            name VARCHAR(255) NOT NULL,
            estimated_velocity INTEGER DEFAULT NULL,
            actual_velocity INTEGER DEFAULT NULL,
            status VARCHAR(100) NOT NULL,
            project_id VARCHAR(255) NOT NULL,
            team_id VARCHAR(255) NOT NULL,
            ended_at DATETIME DEFAULT NULL, PRIMARY KEY(id))'
        );
        $this->addSql('CREATE UNIQUE INDEX unique_sprint_index ON backlog_sprints (name, project_id)');
        $this->addSql('CREATE TABLE backlog_team_members (
            id INTEGER NOT NULL,
            team_id VARCHAR(255) NOT NULL,
            member_id VARCHAR(255) NOT NULL,
            PRIMARY KEY(id))'
        );
        $this->addSql('CREATE INDEX IDX_13602B65296CD8AE ON backlog_team_members (team_id)');
        $this->addSql('CREATE UNIQUE INDEX UNIQ_13602B65296CD8AE7597D3FE ON backlog_team_members (team_id, member_id)');
        $this->addSql('CREATE TABLE backlog_persons (
            id VARCHAR(255) NOT NULL,
            name VARCHAR(255) NOT NULL,
            PRIMARY KEY(id))'
        );
        $this->addSql('CREATE TABLE backlog_teams (
            id VARCHAR(255) NOT NULL,
            name VARCHAR(255) NOT NULL,
            PRIMARY KEY(id))'
        );
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() !== 'sqlite', 'Migration can only be executed safely on \'sqlite\'.');

        $this->addSql('DROP TABLE backlog_projects');
        $this->addSql('DROP TABLE backlog_commitments');
        $this->addSql('DROP TABLE backlog_sprints');
        $this->addSql('DROP TABLE backlog_team_members');
        $this->addSql('DROP TABLE backlog_persons');
        $this->addSql('DROP TABLE backlog_teams');
    }
}
