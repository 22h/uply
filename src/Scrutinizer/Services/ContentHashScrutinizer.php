<?php

namespace App\Scrutinizer\Services;

use App\Entity\Unit\ContentHash;
use App\Entity\Unit\UnitInterface;
use App\Notification\NotificationData;
use App\Repository\Unit\ContentHashRepository;
use App\Scrutinizer\AbstractScrutinizer;
use App\Service\ContentHashService;
use Symfony\Component\Routing\Generator\UrlGeneratorInterface;

/**
 * StatusCode
 *
 * @author Magnus Reiß <info@magnus-reiss.de>
 */
class ContentHashScrutinizer extends AbstractScrutinizer
{
    /**
     * @var ContentHashService
     */
    private $contentHashService;

    /**
     * @var UrlGeneratorInterface
     */
    private $urlGenerator;

    /**
     * @param ContentHashRepository $repository
     * @param ContentHashService    $contentHashService
     * @param UrlGeneratorInterface $urlGenerator
     */
    public function __construct(
        ContentHashRepository $repository,
        ContentHashService $contentHashService,
        UrlGeneratorInterface $urlGenerator
    ) {
        $this->contentHashService = $contentHashService;

        parent::__construct($repository);
        $this->urlGenerator = $urlGenerator;
    }

    /**
     * @inheritDoc
     */
    public function scrutinize(UnitInterface $unit): NotificationData
    {
        if (!($unit instanceof ContentHash)) {
            throw new \Exception('invalid unit');
        }
        $this->logger->info('status code scrutinizer');

        $notificationData = $this->checkContentHash($unit->getId(), $unit->getUrl(), $unit->getHash());

        return $notificationData;
    }

    /**
     * @param int    $id
     * @param string $url
     * @param string $expectedHash
     *
     * @return NotificationData
     */
    private function checkContentHash(int $id, string $url, string $expectedHash): NotificationData
    {
        try {
            $actualHash = $this->contentHashService->getContentHashFromUrl($url);
        } catch (\Exception $exception) {
            return $this->notificationDataFactory->createErrorNotificationData(
                'content_hash.error',
                ['%url%' => $url, '%exception%' => $exception->getMessage()]
            );
        }

        if ($actualHash === $expectedHash) {
            return $this->notificationDataFactory->createSuccessNotificationData(
                'content_hash.success',
                [
                    '%url%'          => $url,
                    '%expectedHash%' => $expectedHash,
                ]
            );
        } else {
            return $this->notificationDataFactory->createDangerNotificationData(
                'content_hash.danger',
                [
                    '%url%'          => $url,
                    '%actualHash%'   => $actualHash,
                    '%expectedHash%' => $expectedHash,
                    '%refreshUrl%'   => $this->generateRefreshUrl($id),
                ]
            );
        }
    }

    /**
     * @param int $id
     *
     * @return string
     */
    private function generateRefreshUrl(int $id): string
    {
        return $this->urlGenerator->generate(
            'command_content_hash_refresh',
            ['id' => $id],
            UrlGeneratorInterface::ABSOLUTE_URL
        );
    }
}
