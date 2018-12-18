<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

/**
 * StatusController
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
     * @return Response
     * @throws \Exception
     */
    public function overview(): Response
    {
        return new Response('', Response::HTTP_NOT_FOUND);
    }
}
