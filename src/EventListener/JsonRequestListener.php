<?php

namespace App\EventListener;

use Symfony\Component\HttpKernel\Event\RequestEvent;
use Symfony\Component\HttpKernel\Exception\HttpException;

class JsonRequestListener
{
    private const CONTENT_TYPE = 'application/json';

    public function onKernelRequest(RequestEvent $event): void
    {
        $request = $event->getRequest();

        if ($request->headers->get('Content-Type') !== self::CONTENT_TYPE) {
            throw new HttpException(400, 'wrong_content_type. ' . self::CONTENT_TYPE . ' required!');
        }
    }
}
