<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

final class Version20260423084551 extends AbstractMigration
{
    public function getDescription(): string
    {
        return 'Change "price" field of "app_book" table, from "int" to "float"';
    }

    public function up(Schema $schema): void
    {
        $this->addSql('ALTER TABLE app_book ALTER price TYPE DOUBLE PRECISION');
    }

    public function down(Schema $schema): void
    {
        $this->addSql('ALTER TABLE app_book ALTER price TYPE SMALLINT');
    }
}
