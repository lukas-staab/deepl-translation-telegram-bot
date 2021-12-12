<?php

namespace cli;

use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;

class DeeplUsageCommand extends \Symfony\Component\Console\Command\Command
{
    protected static $defaultName = 'deepl:usage';
    protected static $defaultDescription = 'Get deepL usage %';

    protected function execute(InputInterface $input, OutputInterface $output) : int
    {
        $deepl = \DeeplApi::make();
        $usage = $deepl->getUsage();
        $text = var_export($usage, true);
        $output->writeln($text);
        return Command::SUCCESS;
    }
}