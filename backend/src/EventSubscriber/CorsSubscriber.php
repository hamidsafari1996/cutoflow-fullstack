<?php

namespace App\EventSubscriber;

use Symfony\Component\EventDispatcher\EventSubscriberInterface;
use Symfony\Component\HttpKernel\Event\RequestEvent;
use Symfony\Component\HttpKernel\Event\ResponseEvent;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpKernel\KernelEvents;

class CorsSubscriber implements EventSubscriberInterface
{
    private const ALLOWED_ORIGIN = '*';
    private const ALLOWED_METHODS = 'GET, POST, DELETE, OPTIONS';
    private const ALLOWED_HEADERS = 'Content-Type';

    public static function getSubscribedEvents(): array
    {
        return [
            KernelEvents::REQUEST => ['onKernelRequest', 1024],
            KernelEvents::RESPONSE => ['onKernelResponse', -1024],
        ];
    }

    public function onKernelRequest(RequestEvent $event): void
    {
        $request = $event->getRequest();
        if (!$event->isMainRequest()) {
            return;
        }

        // Handle preflight for API routes
        if ($request->getMethod() === 'OPTIONS' && str_starts_with($request->getPathInfo(), '/customers')) {
            $response = new Response();
            $this->applyCorsHeaders($response);
            $response->setStatusCode(204);
            $event->setResponse($response);
        }
    }

    public function onKernelResponse(ResponseEvent $event): void
    {
        $request = $event->getRequest();
        $response = $event->getResponse();

        if (str_starts_with($request->getPathInfo(), '/customers')) {
            $this->applyCorsHeaders($response);
        }
    }

    private function applyCorsHeaders(Response $response): void
    {
        $response->headers->set('Access-Control-Allow-Origin', self::ALLOWED_ORIGIN);
        $response->headers->set('Access-Control-Allow-Methods', self::ALLOWED_METHODS);
        $response->headers->set('Access-Control-Allow-Headers', self::ALLOWED_HEADERS);
    }
}


