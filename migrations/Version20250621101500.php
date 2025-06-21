<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20250621101500 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql(<<<SQL
        INSERT INTO employee (name, transport_type, travel_distance_one_way_km, workdays_per_week)
        VALUES
            ('Paul', 0, 60.0, 5.0),
            ('Martin', 2, 8.0, 4.0),
            ('Jeroen', 1, 9.0, 5.0),
            ('Tineke', 1, 4.0, 3.0),
            ('Arnout', 3, 23.0, 5.0),
            ('Matthijs', 1, 11.0, 4.5),
            ('Rens', 0, 12.0, 5.0)
        SQL);
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('DELETE FROM employee WHERE name IN (\'Paul\', \'Martin\', \'Jeroen\', \'Tineke\', \'Arnout\', \'Matthijs\', \'Rens\')');
    }
}
