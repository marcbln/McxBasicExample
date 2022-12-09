<?php

declare(strict_types=1);

namespace Mcx\BasicExample\Subscriber;

use Psr\Log\LoggerInterface;
use Shopware\Core\Content\Product\ProductEntity;
use Shopware\Core\Content\Product\ProductEvents;
use Shopware\Core\Framework\DataAbstractionLayer\Event\EntityLoadedEvent;
use Symfony\Component\EventDispatcher\EventSubscriberInterface;
use Symfony\Component\HttpKernel\Event\FinishRequestEvent;
use Symfony\Component\HttpKernel\Event\KernelEvent;
use Symfony\Component\HttpKernel\Event\RequestEvent;
use Symfony\Component\HttpKernel\Event\ResponseEvent;
use Symfony\Component\HttpKernel\Event\TerminateEvent;
use Symfony\Component\HttpKernel\KernelEvents;
use WhyooOs\Util\UtilDebug;

class KernelEventsSubscriber implements EventSubscriberInterface
{
    private LoggerInterface $logger;

    public function __construct(LoggerInterface $logger)
    {
        $this->logger = $logger;
    }

    public static function getSubscribedEvents()
    {
        return [
//            KernelEvents::REQUEST              => 'onKernelRequestEvent',
//            KernelEvents::RESPONSE             => 'onKernelResponseEvent',
            KernelEvents::TERMINATE            => 'onKernelTerminateEvent',
//            KernelEvents::FINISH_REQUEST       => 'onKernelFinishRequestEvent',
//            KernelEvents::CONTROLLER           => 'onKernelEvent',
//            KernelEvents::CONTROLLER_ARGUMENTS => 'onKernelEvent',
//            KernelEvents::EXCEPTION            => 'onKernelEvent',
//            KernelEvents::VIEW                 => 'onKernelEvent',
        ];
    }

    public function onKernelRequestEvent(RequestEvent $event): void
    {
        $this->logger->notice("++++++++ ProductEventsSubscriber::onKernelRequestEvent(): " . $event->getRequest()->getPathInfo());
    }

    public function onKernelResponseEvent(ResponseEvent $event): void
    {
        $this->logger->notice("++++++++ ProductEventsSubscriber::onKernelResponseEvent(): " . $event->getResponse()->getStatusCode());
    }

    public function onKernelFinishRequestEvent(FinishRequestEvent $event): void
    {
        $this->logger->notice("++++++++ ProductEventsSubscriber::onKernelFinishRequestEvent(): ");
    }

    public function onKernelTerminateEvent(TerminateEvent $event): void
    {
        $req = $event->getRequest()->getMethod() . ' ' . $event->getRequest()->getPathInfo();
        $code = $event->getResponse()->getStatusCode();
        $this->logger->critical("++++++++ ProductEventsSubscriber::onKernelTerminateEvent() {$req} -> $code");
    }


    public function onKernelEvent($event): void
    {
        $this->logger->notice("++++++++ ProductEventsSubscriber::onKernelEvent(): " . get_class($event));
    }

}
