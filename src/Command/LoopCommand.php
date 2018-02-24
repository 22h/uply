<?php

namespace App\Command;

use App\Command\Loop\StatusCommand;
use App\Command\Loop\ContinueCommand;
use App\Command\Loop\SleepCommand;
use App\Command\Loop\StartCommand;
use Psr\Log\LoggerAwareInterface;
use Psr\Log\LoggerAwareTrait;
use Symfony\Bundle\FrameworkBundle\Command\ContainerAwareCommand;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Console\Style\SymfonyStyle;

/**
 * LoopCommand
 *
 * @author Magnus Reiß <info@magnus-reiss.de>
 */
class LoopCommand extends ContainerAwareCommand implements LoggerAwareInterface
{
    use LoggerAwareTrait;

    const COMMAND_NAME = 'monitor:loop';

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
        (new SymfonyStyle($input, $output))->listing(
            [
                ContinueCommand::COMMAND_NAME,
                SleepCommand::COMMAND_NAME,
                StartCommand::COMMAND_NAME,
                StatusCommand::COMMAND_NAME,
            ]
        );
    }
}