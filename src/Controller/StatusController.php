<?php

namespace App\Controller;

use App\Monitor\UnitServiceChain;
use App\Service\MonitoringLoopService;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

/**
 * StatusController
 *
 * @Route("/status")
 *
 * @author Magnus Reiß <info@magnus-reiss.de>
 */
class StatusController extends AbstractController
{
    /**
     * @var string
     */
    private $apiKey;

    /**
     * StatusController constructor.
     *
     * @param string $apiKey
     */
    public function __construct(string $apiKey)
    {
        $this->apiKey = $apiKey;
    }

    /**
     * @Route("/", name="status")
     *
     * @param Request $request
     * @param MonitoringLoopService $monitoringLoopService
     * @param UnitServiceChain $unitServiceChain
     *
     * @return JsonResponse
     * @throws \Exception
     */
    public function overview(
        Request $request,
        MonitoringLoopService $monitoringLoopService,
        UnitServiceChain $unitServiceChain
    ): JsonResponse {
        if ($request->get('key') !== $this->apiKey) {
            return $this->json(
                ['error' => 'unauthorized', 'status_code' => Response::HTTP_UNAUTHORIZED],
                Response::HTTP_UNAUTHORIZED
            );
        }

        $output = [];
        $output['status'] = ($monitoringLoopService->isLoopProcessRunning()) ? 'running' : 'stopped';
        $output['available_units'] = $unitServiceChain->getIdentifier();
        $output['triggered_units'] = $monitoringLoopService->getAllTriggeredUnits();
        $output['count_units'] = $monitoringLoopService->countAllUnits();

        return $this->json($output);

    }
}
