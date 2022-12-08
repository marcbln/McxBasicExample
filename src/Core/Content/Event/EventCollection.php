<?php declare(strict_types=1);

namespace Mcx\BasicExample\Core\Content\Event;

use Shopware\Core\Framework\DataAbstractionLayer\EntityCollection;

/**
 * 12/2022 created
 * @extends EntityCollection<EventEntity>
 */
class EventCollection extends EntityCollection
{
    protected function getExpectedClass(): string
    {
        return EventEntity::class;
    }
}