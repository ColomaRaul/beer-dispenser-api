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
        $this->addSql('CREATE TABLE dispenser (id UUID not null, flow_volume float not null, status VARCHAR(50) not null, price_by_litre float not null, open_time TIMESTAMP WITH TIME ZONE, close_time TIMESTAMP WITH TIME ZONE, PRIMARY KEY (id))');
    }

    public function down(Schema $schema): void
    {
        $this->addSql('DROP TABLE dispenser');
    }
}
