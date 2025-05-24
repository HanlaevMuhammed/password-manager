<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20250524134350 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql(<<<'SQL'
            CREATE TABLE account_groups (id SERIAL NOT NULL, user_id INT NOT NULL, name VARCHAR(255) NOT NULL, PRIMARY KEY(id))
        SQL);
        $this->addSql(<<<'SQL'
            CREATE INDEX IDX_D759F703A76ED395 ON account_groups (user_id)
        SQL);
        $this->addSql(<<<'SQL'
            CREATE TABLE accounts (id SERIAL NOT NULL, user_id INT NOT NULL, group_id INT DEFAULT NULL, service_name VARCHAR(255) NOT NULL, login VARCHAR(255) NOT NULL, password VARCHAR(255) NOT NULL, PRIMARY KEY(id))
        SQL);
        $this->addSql(<<<'SQL'
            CREATE INDEX IDX_CAC89EACA76ED395 ON accounts (user_id)
        SQL);
        $this->addSql(<<<'SQL'
            CREATE INDEX IDX_CAC89EACFE54D947 ON accounts (group_id)
        SQL);
        $this->addSql(<<<'SQL'
            ALTER TABLE account_groups ADD CONSTRAINT FK_D759F703A76ED395 FOREIGN KEY (user_id) REFERENCES users (id) NOT DEFERRABLE INITIALLY IMMEDIATE
        SQL);
        $this->addSql(<<<'SQL'
            ALTER TABLE accounts ADD CONSTRAINT FK_CAC89EACA76ED395 FOREIGN KEY (user_id) REFERENCES users (id) NOT DEFERRABLE INITIALLY IMMEDIATE
        SQL);
        $this->addSql(<<<'SQL'
            ALTER TABLE accounts ADD CONSTRAINT FK_CAC89EACFE54D947 FOREIGN KEY (group_id) REFERENCES account_groups (id) NOT DEFERRABLE INITIALLY IMMEDIATE
        SQL);
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql(<<<'SQL'
            CREATE SCHEMA public
        SQL);
        $this->addSql(<<<'SQL'
            ALTER TABLE account_groups DROP CONSTRAINT FK_D759F703A76ED395
        SQL);
        $this->addSql(<<<'SQL'
            ALTER TABLE accounts DROP CONSTRAINT FK_CAC89EACA76ED395
        SQL);
        $this->addSql(<<<'SQL'
            ALTER TABLE accounts DROP CONSTRAINT FK_CAC89EACFE54D947
        SQL);
        $this->addSql(<<<'SQL'
            DROP TABLE account_groups
        SQL);
        $this->addSql(<<<'SQL'
            DROP TABLE accounts
        SQL);
    }
}
