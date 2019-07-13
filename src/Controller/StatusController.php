<?php

namespace App\Controller;

use App\Service\StatusService;
use Exception;
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
 * @deprecated do not use this route anymore, use /src/Controller/Api/StatusController with token
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
     * @param Request       $request
     * @param StatusService $statusService
     *
     * @return JsonResponse
     * @throws Exception
     */
    public function overview(Request $request, StatusService $statusService): JsonResponse
    {
        if ($request->get('key') !== $this->apiKey) {
            return $this->json(
                ['error' => 'unauthorized', 'status_code' => Response::HTTP_UNAUTHORIZED],
                Response::HTTP_UNAUTHORIZED
            );
        }
        $output = [];
        $output['status'] = ($statusService->isLoopProcessRunning()) ? 'running' : 'stopped';
        $output['available_units'] = $statusService->getAvailableUnits();
        $output['triggered_units'] = $statusService->getAllTriggeredUnits();
        $output['count_units'] = $statusService->countAllUnits();

        return $this->json($output);
    }
}