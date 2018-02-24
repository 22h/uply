<?php

namespace App\Command\Monitor;

use App\Entity\Unit\StatusCode;
use App\Repository\Unit\StatusCodeRepository;
use App\Service\EventFactory;
use App\Service\EventService;
use App\Service\HttpHeader;
use Symfony\Bundle\FrameworkBundle\Command\ContainerAwareCommand;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Console\Question\ConfirmationQuestion;
use Symfony\Component\Console\Question\Question;

/**
 * StatusCodeCommand
 *
 * @author Magnus Reiß <info@magnus-reiss.de>
 */
class StatusCodeCommand extends ContainerAwareCommand
{

    const COMMAND_NAME = 'monitor:monitor:status-code';

    /**
     * @var HttpHeader
     */
    private $httpHeader;

    /**
     * @var StatusCodeRepository
     */
    private $repository;

    /**
     * @var EventFactory
     */
    private $eventFactory;

    /**
     * @var EventService
     */
    private $eventService;

    /**
     * StatusCodeCommand constructor.
     *
     * @param HttpHeader           $httpHeader
     * @param StatusCodeRepository $repository
     * @param EventFactory         $eventFactory
     * @param EventService         $eventService
     */
    public function __construct(
        HttpHeader $httpHeader,
        StatusCodeRepository $repository,
        EventFactory $eventFactory,
        EventService $eventService
    ) {
        $this->httpHeader = $httpHeader;
        $this->repository = $repository;
        $this->eventFactory = $eventFactory;
        $this->eventService = $eventService;

        parent::__construct();
    }

    /**
     * @return void
     */
    protected function configure(): void
    {
        $this->setName(self::COMMAND_NAME);
    }

    /**
     * @param InputInterface  $input
     * @param OutputInterface $output
     *
     * @return int|null|void
     * @throws \Doctrine\ORM\ORMException
     * @throws \Doctrine\ORM\OptimisticLockException
     * @throws \Exception
     */
    protected function execute(InputInterface $input, OutputInterface $output)
    {
        $helper = $this->getHelper('question');

        $question = new Question('Which URL should be checked for its status code? ');
        $pageUrl = $helper->ask($input, $output, $question);

        $detectedStatusCode = $this->httpHeader->requestStatusCode($pageUrl);

        $question = new ConfirmationQuestion('Should the status code '.$detectedStatusCode.' be adopted? (Y/n) ', true);
        $chooseDetectedStatusCode = $helper->ask($input, $output, $question);

        if (!$chooseDetectedStatusCode) {
            $question = new Question('Which status code should be checked for? ', 200);
            $statusCode = $helper->ask($input, $output, $question);
        } else {
            $statusCode = $detectedStatusCode;
        }

        $question = new Question('What is the time interval between tests, in minutes? (5) ', 5);
        $idleTime = (int)$helper->ask($input, $output, $question);

        $unit = new StatusCode();
        $unit->setUrl($pageUrl);
        $unit->setStatusCode((int)$statusCode);
        $unit->setIdleTime($idleTime);
        $unit->setBustCache(false);
        $this->repository->save($unit);

        $event = $this->eventFactory->buildEventByMonitorUnit($unit);
        $this->eventService->storeEvent($event);

        dump($unit);
    }
}