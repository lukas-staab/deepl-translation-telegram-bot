<?php

namespace cli;

use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;

class DeeplLanguagesCommand extends \Symfony\Component\Console\Command\Command
{
    protected static $defaultName = 'deepl:languages';
    protected static $defaultDescription = 'Get deepL languages';

    protected function execute(InputInterface $input, OutputInterface $output) : int
    {
        $deepl = \DeeplApi::make();
        $langs = $deepl->getSupportedLanguages();
        $text = var_export($langs, true);
        $output->writeln($text);
        return Command::SUCCESS;
    }
}