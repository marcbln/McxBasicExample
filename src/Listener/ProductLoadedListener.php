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

        $ret = [];
        foreach($entityLoadedEvent->getEntities() as $entity) {
            // UtilDebug::dd($entity->getVars(), $entityLoadedEvent);
            $ret[] = $entity->getVars()['translated']['name'];
        }
        $this->logger->notice("ProductLoadedListener: products loaded: " . implode(", ", $ret));
    }
}
