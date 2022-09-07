<?php

namespace App\EventListener;

use Symfony\Component\EventDispatcher\EventSubscriberInterface;
use Symfony\Component\HttpKernel\Event\RequestEvent;

class KernelSubscriber implements EventSubscriberInterface
{
    public static function getSubscribedEvents()
    {
        return[RequestEvent::class => 'onKernelRequest'];
    }

    public function onKernelRequest(RequestEvent $event)
    {
        dd($event->getRequest());
    }

}