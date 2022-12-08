<?php declare(strict_types=1);

namespace Mcx\BasicExample\Migration;

use Doctrine\DBAL\Connection;
use Shopware\Core\Framework\Migration\MigrationStep;

/**
 * created with command: bin/console database:create-migration -p McxBasicExample --name AddTableXxx

 * 12/2022 created
 */
class Migration1670540191AddTableXxx extends MigrationStep
{
    public function getCreationTimestamp(): int
    {
        return 1670540191;
    }

    public function update(Connection $connection): void
    {
        // implement update
    }

    public function updateDestructive(Connection $connection): void
    {
        // implement update destructive
    }
}
