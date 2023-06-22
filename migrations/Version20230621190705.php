<?php

declare(strict_types=1);

namespace App\Migrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

final class Version20230621190705 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        $this->addSql('CREATE TABLE dispenser (id UUID PRIMARY KEY, flow_volume float not null, price_by_litre float not null, amount integer default 0)');
        $this->addSql('CREATE TABLE dispenser_event (id SERIAL PRIMARY KEY, dispenser_id UUID not null, updated_at TIMESTAMP WITH TIME ZONE, opened_at TIMESTAMP WITH TIME ZONE, closed_at TIMESTAMP WITH TIME ZONE, total_spent float default 0.0)');
    }

    public function down(Schema $schema): void
    {
        $this->addSql('DROP TABLE dispenser');
        $this->addSql('DROP TABLE dispenser_event');
    }
}
