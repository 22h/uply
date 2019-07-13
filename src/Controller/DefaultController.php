<?php

namespace App\Controller;

use App\Service\UserAgentService;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

/**
 * DefaultController
 *
 * @Route("/")
 *
 * @author Magnus Reiß <info@magnus-reiss.de>
 */
class DefaultController extends AbstractController
{

    /**
     * @Route("/", name="default")
     *
     * @param UserAgentService $userAgentService
     *
     * @return Response
     */
    public function overview(UserAgentService $userAgentService): Response
    {
        return $this->render('index.html.twig', ['useragent' => $userAgentService->getUserAgent()]);
    }
}
