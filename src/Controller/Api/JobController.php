<?php

namespace App\Controller\Api;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

/**
 * DefaultController
 *
 * @Route("/api/job/")
 *
 * @author Magnus Reiß <info@magnus-reiss.de>
 */
class JobController extends AbstractController
{

    /**
     * @Route("/", name="api_default_overview")
     *
     * @return Response
     * @throws \Exception
     */
    public function overview(): Response
    {
        return new JsonResponse(['test' => '123'], Response::HTTP_OK);
    }
}
