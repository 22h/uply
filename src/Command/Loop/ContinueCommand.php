<?php

namespace App\Command\Loop;

use App\Service\MonitoringLoopService;
use Symfony\Bundle\FrameworkBundle\Command\ContainerAwareCommand;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Console\Style\SymfonyStyle;

/**
 * SleepCommand
 *
 * @author Magnus Reiß <info@magnus-reiss.de>
 */
class ContinueCommand extends ContainerAwareCommand
{

    const COMMAND_NAME = 'monitor:loop:continue';

    /**
     * @var MonitoringLoopService
     */
    private $monitoringLoopService;

    /**
     * StopCommand constructor.
     *
     * @param MonitoringLoopService $monitoringLoopService
     */
    public function __construct(MonitoringLoopService $monitoringLoopService)
    {
        $this->monitoringLoopService = $monitoringLoopService;

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
     */
    protected function execute(InputInterface $input, OutputInterface $output): void
    {
        $symfonyStyle = new SymfonyStyle($input, $output);

        try {
            $this->monitoringLoopService->removeSleepEvent();
            $symfonyStyle->success('loop is continue now');
        } catch (\Exception $exception) {
            $symfonyStyle->note('loop already running, no sleep event found');
        }
    }
}