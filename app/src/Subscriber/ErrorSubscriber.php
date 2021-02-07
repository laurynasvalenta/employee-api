<?php

namespace App\Subscriber;

use App\Exception\ApiExceptionInterface;
use Symfony\Component\EventDispatcher\EventSubscriberInterface;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpKernel\Event\ExceptionEvent;
use Symfony\Component\HttpKernel\KernelEvents;
use Throwable;

class ErrorSubscriber implements EventSubscriberInterface
{
    /**
     * @inheritDoc
     */
    public static function getSubscribedEvents()
    {
        return [
            KernelEvents::EXCEPTION => 'onException',
        ];
    }

    /**
     * @param ExceptionEvent $event
     */
    public function onException(ExceptionEvent $event): void
    {
        $errorMessage = $this->determineErrorMessage($event->getThrowable());
        $httpCode = $this->determineResponseCode($event->getThrowable());

        $response = new JsonResponse(
            ['message' => $errorMessage],
            $httpCode
        );

        $event->setResponse($response);
    }

    /**
     * @param Throwable $throwable
     *
     * @return string
     */
    private function determineErrorMessage(Throwable $throwable): string
    {
        return $throwable->getMessage();
    }

    /**
     * @param Throwable $throwable
     *
     * @return int
     */
    private function determineResponseCode(Throwable $throwable): int
    {
        if ($throwable instanceof ApiExceptionInterface) {
            return $throwable->getHttpCode();
        }

        return Response::HTTP_INTERNAL_SERVER_ERROR;
    }
}
