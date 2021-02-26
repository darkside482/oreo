<?php

namespace App\EventListener;

use App\Enum\ErrorCode;
use App\Exception\ClientErrorException;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpKernel\Event\ExceptionEvent;
use Symfony\Component\HttpKernel\Exception\HttpException;

class ExceptionListener
{
    public function onKernelException(ExceptionEvent $event): void
    {
        $e = $event->getThrowable();

        if ($e instanceof ClientErrorException) {
            $event->setResponse(
                new JsonResponse([
                    'error' => $e->getMessage(),
                    'code' => $e->getCode()
                ], Response::HTTP_BAD_REQUEST));
        } elseif ($e instanceof HttpException && getenv('APP_ENV') === 'prod') {
           $event->setResponse(new JsonResponse([
               'error' => $e->getMessage(),
               'code' => $e->getCode()
           ], $e->getStatusCode()));
        }
    }
}
