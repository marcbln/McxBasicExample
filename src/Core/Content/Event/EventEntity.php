<?php declare(strict_types=1);

namespace Mcx\BasicExample\Core\Content\Event;

use Shopware\Core\Framework\DataAbstractionLayer\EntityIdTrait;

/**
 * 12/2022 created
 */
class EventEntity
{
    use EntityIdTrait;

    private string $name; // eg "product.loaded"

    public function getName(): string
    {
        return $this->name;
    }

    public function setName(string $name): void
    {
        $this->name = $name;
    }

}