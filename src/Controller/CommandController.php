<?php

namespace App\Controller;

use App\Entity\Unit\ContentHash;
use App\Entity\Unit\StatusCode;
use App\Repository\Unit\ContentHashRepository;
use App\Repository\Unit\StatusCodeRepository;
use App\Service\ContentHashService;
use App\Service\HttpHeader;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

/**
 * StatusController
 *
 * @Route("/command")
 *
 * @author Magnus Reiß <info@magnus-reiss.de>
 */
class CommandController extends AbstractController
{

    /**
     * @Route("/content-hash/refresh/{id}", name="command_content_hash_refresh")
     *
     * @param ContentHash           $contentHash
     * @param ContentHashService    $contentHashService
     * @param ContentHashRepository $contentHashRepository
     *
     * @return Response
     */
    public function contentHashRefresh(
        ContentHash $contentHash,
        ContentHashService $contentHashService,
        ContentHashRepository $contentHashRepository
    ): Response {
        $hash = $contentHashService->getContentHashFromUrl($contentHash->getUrl());

        $contentHash->setHash($hash);
        $contentHashRepository->save($contentHash);

        return new Response('content hash changed', Response::HTTP_OK);
    }

    /**
     * @Route("/status-code/refresh/{id}", name="command_status_code_refresh")
     *
     * @param StatusCode           $statusCode
     * @param HttpHeader           $httpHeader
     * @param StatusCodeRepository $statusCodeRepository
     *
     * @return Response
     */
    public function statusCodeRefresh(
        StatusCode $statusCode,
        HttpHeader $httpHeader,
        StatusCodeRepository $statusCodeRepository
    ): Response {
        $code = $httpHeader->requestStatusCode($statusCode->getUrl());

        $statusCode->setStatusCode($code);
        $statusCodeRepository->save($statusCode);

        return new Response('content hash changed', Response::HTTP_OK);
    }
}
