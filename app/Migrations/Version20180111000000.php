<?php declare(strict_types = 1);

namespace Star\BacklogVelocity\Migrations;

use Doctrine\DBAL\Migrations\AbstractMigration;
use Doctrine\DBAL\Schema\Schema;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
class Version20180111000000 extends AbstractMigration
{
    public function up(Schema $schema)
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() !== 'sqlite', 'Migration can only be executed safely on \'sqlite\'.');

        $this->addSql('DROP INDEX unique_commitment_index');
        $this->addSql('DROP INDEX IDX_4268BB2F8C24077B');
        $this->addSql('CREATE TEMPORARY TABLE __temp__backlog_commitments AS SELECT id, sprint_id, man_days, member_id FROM backlog_commitments');
        $this->addSql('DROP TABLE backlog_commitments');
        $this->addSql('CREATE TABLE backlog_commitments (id INTEGER NOT NULL, sprint_id VARCHAR(255) NOT NULL COLLATE BINARY, man_days INTEGER NOT NULL, member_id VARCHAR(255) NOT NULL COLLATE BINARY, PRIMARY KEY(id), CONSTRAINT FK_4268BB2F8C24077B FOREIGN KEY (sprint_id) REFERENCES backlog_sprints (id) NOT DEFERRABLE INITIALLY IMMEDIATE)');
        $this->addSql('INSERT INTO backlog_commitments (id, sprint_id, man_days, member_id) SELECT id, sprint_id, man_days, member_id FROM __temp__backlog_commitments');
        $this->addSql('DROP TABLE __temp__backlog_commitments');
        $this->addSql('CREATE UNIQUE INDEX unique_commitment_index ON backlog_commitments (sprint_id, member_id)');
        $this->addSql('CREATE INDEX IDX_4268BB2F8C24077B ON backlog_commitments (sprint_id)');
        $this->addSql('ALTER TABLE backlog_sprints ADD COLUMN current_focus INTEGER DEFAULT NULL');

        $this->addSql('
UPDATE backlog_sprints 
SET current_focus = (
  SELECT (sub_s.actual_velocity / SUM(sub_c.man_days) * 100)
  FROM backlog_sprints AS sub_s
  INNER JOIN backlog_commitments AS sub_c ON sub_c.sprint_id = sub_s.id 
  WHERE sub_s.status = "closed" AND sub_s.current_focus IS NULL
)
WHERE status = "closed" AND current_focus IS NULL             
');

        $this->addSql('DROP INDEX UNIQ_13602B65296CD8AE7597D3FE');
        $this->addSql('DROP INDEX IDX_13602B65296CD8AE');
        $this->addSql('CREATE TEMPORARY TABLE __temp__backlog_team_members AS SELECT id, team_id, member_id FROM backlog_team_members');
        $this->addSql('DROP TABLE backlog_team_members');
        $this->addSql('CREATE TABLE backlog_team_members (id INTEGER NOT NULL, team_id VARCHAR(255) NOT NULL COLLATE BINARY, member_id VARCHAR(255) NOT NULL COLLATE BINARY, PRIMARY KEY(id), CONSTRAINT FK_13602B65296CD8AE FOREIGN KEY (team_id) REFERENCES backlog_teams (id) NOT DEFERRABLE INITIALLY IMMEDIATE)');
        $this->addSql('INSERT INTO backlog_team_members (id, team_id, member_id) SELECT id, team_id, member_id FROM __temp__backlog_team_members');
        $this->addSql('DROP TABLE __temp__backlog_team_members');
        $this->addSql('CREATE UNIQUE INDEX UNIQ_13602B65296CD8AE7597D3FE ON backlog_team_members (team_id, member_id)');
        $this->addSql('CREATE INDEX IDX_13602B65296CD8AE ON backlog_team_members (team_id)');
    }

    public function down(Schema $schema)
    {
        // todo see https://stackoverflow.com/questions/31807524/sqlite-how-to-divide-two-count-results-in-the-same-query
        var_dump(
            $this->connection->fetchAll('SELECT name, current_focus FROM backlog_sprints WHERE status = "closed"'),
            $this->connection->fetchAll(' 
  SELECT sub_s.name, sub_s.actual_velocity, SUM(sub_c.man_days), (sub_s.actual_velocity / SUM(sub_c.man_days) * 100)
  FROM backlog_sprints AS sub_s
  INNER JOIN backlog_commitments AS sub_c ON sub_c.sprint_id = sub_s.id 
  WHERE sub_s.status = "closed" 
')
        );
        // this down() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() !== 'sqlite', 'Migration can only be executed safely on \'sqlite\'.');

        $this->addSql('DROP INDEX IDX_4268BB2F8C24077B');
        $this->addSql('DROP INDEX unique_commitment_index');
        $this->addSql('CREATE TEMPORARY TABLE __temp__backlog_commitments AS SELECT id, sprint_id, man_days, member_id FROM backlog_commitments');
        $this->addSql('DROP TABLE backlog_commitments');
        $this->addSql('CREATE TABLE backlog_commitments (id INTEGER NOT NULL, sprint_id VARCHAR(255) NOT NULL, man_days INTEGER NOT NULL, member_id VARCHAR(255) NOT NULL, PRIMARY KEY(id))');
        $this->addSql('INSERT INTO backlog_commitments (id, sprint_id, man_days, member_id) SELECT id, sprint_id, man_days, member_id FROM __temp__backlog_commitments');
        $this->addSql('DROP TABLE __temp__backlog_commitments');
        $this->addSql('CREATE INDEX IDX_4268BB2F8C24077B ON backlog_commitments (sprint_id)');
        $this->addSql('CREATE UNIQUE INDEX unique_commitment_index ON backlog_commitments (sprint_id, member_id)');
        $this->addSql('DROP INDEX unique_sprint_index');
        $this->addSql('CREATE TEMPORARY TABLE __temp__backlog_sprints AS SELECT id, name, estimated_velocity, actual_velocity, status, project_id, team_id, created_at, started_at, ended_at FROM backlog_sprints');
        $this->addSql('DROP TABLE backlog_sprints');
        $this->addSql('CREATE TABLE backlog_sprints (id VARCHAR(255) NOT NULL, name VARCHAR(255) NOT NULL, estimated_velocity INTEGER DEFAULT NULL, actual_velocity INTEGER DEFAULT NULL, status VARCHAR(100) NOT NULL, project_id VARCHAR(255) NOT NULL, team_id VARCHAR(255) NOT NULL, created_at DATETIME NOT NULL, started_at DATETIME DEFAULT NULL, ended_at DATETIME DEFAULT NULL, PRIMARY KEY(id))');
        $this->addSql('INSERT INTO backlog_sprints (id, name, estimated_velocity, actual_velocity, status, project_id, team_id, created_at, started_at, ended_at) SELECT id, name, estimated_velocity, actual_velocity, status, project_id, team_id, created_at, started_at, ended_at FROM __temp__backlog_sprints');
        $this->addSql('DROP TABLE __temp__backlog_sprints');
        $this->addSql('CREATE UNIQUE INDEX unique_sprint_index ON backlog_sprints (name, project_id)');
        $this->addSql('DROP INDEX IDX_13602B65296CD8AE');
        $this->addSql('DROP INDEX UNIQ_13602B65296CD8AE7597D3FE');
        $this->addSql('CREATE TEMPORARY TABLE __temp__backlog_team_members AS SELECT id, team_id, member_id FROM backlog_team_members');
        $this->addSql('DROP TABLE backlog_team_members');
        $this->addSql('CREATE TABLE backlog_team_members (id INTEGER NOT NULL, team_id VARCHAR(255) NOT NULL, member_id VARCHAR(255) NOT NULL, PRIMARY KEY(id))');
        $this->addSql('INSERT INTO backlog_team_members (id, team_id, member_id) SELECT id, team_id, member_id FROM __temp__backlog_team_members');
        $this->addSql('DROP TABLE __temp__backlog_team_members');
        $this->addSql('CREATE INDEX IDX_13602B65296CD8AE ON backlog_team_members (team_id)');
        $this->addSql('CREATE UNIQUE INDEX UNIQ_13602B65296CD8AE7597D3FE ON backlog_team_members (team_id, member_id)');
    }
}
