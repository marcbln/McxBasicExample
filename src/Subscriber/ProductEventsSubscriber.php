<?php

declare(strict_types=1);

namespace Mcx\BasicExample\Subscriber;

use Psr\Log\LoggerInterface;
use Shopware\Core\Content\Product\ProductEntity;
use Shopware\Core\Content\Product\ProductEvents;
use Shopware\Core\Framework\DataAbstractionLayer\Event\EntityLoadedEvent;
use Symfony\Component\EventDispatcher\EventSubscriberInterface;
use WhyooOs\Util\UtilDebug;

class ProductEventsSubscriber implements EventSubscriberInterface
{
    private LoggerInterface $logger;

    public function __construct(LoggerInterface $logger)
    {
        $this->logger = $logger;
    }

    public static function getSubscribedEvents()
    {
        return [
            ProductEvents::PRODUCT_LOADED_EVENT => 'onProductsLoaded'
            // ... TODO.. add more events
        ];
    }

    public function onProductsLoaded(EntityLoadedEvent $event): void
    {
        /** @var ProductEntity $productEntity */
        foreach ($event->getEntities() as $productEntity) {
            $ret[] = $productEntity->getVars()['translated']['name'];
            $this->logger->notice("-------- ProductEventsSubscriber::onProductsLoaded(): " . implode(", ", $ret));
        }
    }

}
