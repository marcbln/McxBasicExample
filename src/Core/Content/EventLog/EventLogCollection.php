<?php declare(strict_types=1);

namespace Mcx\BasicExample\Core\Content\EventLog;

use Shopware\Core\Framework\DataAbstractionLayer\EntityCollection;

/**
 * 12/2022 created
 */
class EventLogCollection extends EntityCollection
{
    protected function getExpectedClass(): string
    {
        return EventLogEntity::class;
    }
}