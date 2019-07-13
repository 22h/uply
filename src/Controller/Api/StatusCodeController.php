<?php

namespace App\Controller\Api;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

/**
 * JobController
 *
 * @Route("/api/status-code")
 *
 * @author Magnus Reiß <info@magnus-reiss.de>
 */
class StatusCodeController extends AbstractController
{

    /**
     * @Route("", name="api_status_code_create", methods={"POST"})
     *
     * @return Response
     * @throws \Exception
     */
    public function create(): Response
    {
        return new JsonResponse(['test' => '123'], Response::HTTP_OK);
    }
}
