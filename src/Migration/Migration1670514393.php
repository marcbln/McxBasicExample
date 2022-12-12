<?php declare(strict_types=1);

namespace Mcx\BasicExample\Migration;

use Doctrine\DBAL\Connection;
use Shopware\Core\Framework\Migration\MigrationStep;

class Migration1670514393 extends MigrationStep
{
    public function getCreationTimestamp(): int
    {
        return 1670514395;
    }

    public function update(Connection $connection): void
    {
        // implement update
        $sql = "
        CREATE TABLE IF NOT EXISTS `event_log` (
            `id`                    BINARY(16)   NOT NULL,
            `name`                  VARCHAR(255) NOT NULL,
            `created_at`            DATETIME(3)  NOT NULL,
            `updated_at`            DATETIME(3)
        ) 
        ENGINE = InnoDB
        DEFAULT CHARSET = utf8mb4
        COLLATE = utf8mb4_unicode_ci;

        ";
        $connection->executeStatement($sql);
    }

    public function updateDestructive(Connection $connection): void
    {
        // implement update destructive
    }
}
