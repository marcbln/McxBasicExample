<?php

declare(strict_types=1);

namespace Mcx\BasicExample\Listener;

use Psr\Log\LoggerInterface;
use Shopware\Core\Framework\DataAbstractionLayer\Event\EntityLoadedEvent;
use WhyooOs\Util\UtilDebug;

class ProductLoadedListener
{


    private LoggerInterface $logger;

    public function __construct(LoggerInterface $logger)
    {
        $this->logger = $logger;
    }

    public function onProductLoaded(EntityLoadedEvent $entityLoadedEvent)
    {
        // UtilDebug::dd($entityLoadedEvent->getContext());

        $ret = [];
        foreach($entityLoadedEvent->getEntities() as $entity) {
            $ret[] = $entity->getVars()['translated']['name'];
        }
        $this->logger->notice("ProductLoadedListener: products loaded: " . implode(", ", $ret));
    }
}
