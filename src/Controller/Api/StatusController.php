<?php

namespace App\Controller\Api;

use App\Service\StatusService;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\Routing\Annotation\Route;

/**
 * StatusController
 *
 * @Route("/api/status")
 *
 * @author Magnus Reiß <info@magnus-reiss.de>
 */
class StatusController extends AbstractController
{

    /**
     * @Route("/", name="api_status_index")
     *
     * @param StatusService $statusService
     *
     * @return JsonResponse
     * @throws \Exception
     */
    public function overview(StatusService $statusService): JsonResponse
    {
        $output = [];
        $output['status'] = ($statusService->isLoopProcessRunning()) ? 'running' : 'stopped';
        $output['available_units'] = $statusService->getAvailableUnits();
        $output['triggered_units'] = $statusService->getAllTriggeredUnits();
        $output['count_units'] = $statusService->countAllUnits();

        return $this->json($output);
    }
}
