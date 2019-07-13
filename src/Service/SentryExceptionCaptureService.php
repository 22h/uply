<?php

namespace App\Service;

use Sentry\SentryBundle\SentrySymfonyClient;

/**
 * SentryExceptionCaptureService
 *
 * @author Magnus Reiß <info@magnus-reiss.de>
 */
class SentryExceptionCaptureService
{

    /**
     * @var SentrySymfonyClient
     */
    private $sentryClient;

    /**
     * @param SentrySymfonyClient $sentryClient
     */
    public function __construct(SentrySymfonyClient $sentryClient)
    {
        $this->sentryClient = $sentryClient;
    }

    /**
     * forwardExceptionToSentry
     *
     * @param \Exception $exception
     * @param array      $extraContent
     *
     * @return void
     */
    public function forwardExceptionToSentry(\Exception $exception, array $extraContent = []): void
    {
        if (is_array($extraContent) && count($extraContent) > 0) {
            $this->sentryClient->extra_context($extraContent);
        }

        $this->sentryClient->captureException($exception);
    }


}
