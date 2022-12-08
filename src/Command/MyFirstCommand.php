<?php

declare(strict_types=1);

namespace Mcx\BasicExample\Command;

use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Input\InputOption;
use Symfony\Component\Console\Output\OutputInterface;

class MyFirstCommand extends Command
{
    protected static $defaultName = 'mcx:basic-example:my-first-command';

    public function configure(): void
    {
        $this->addOption('info');
            $this->addOption('batch-size', 'b', InputOption::VALUE_REQUIRED, 'Number of entities per iteration', '50');
    }

    public function run(InputInterface $input, OutputInterface $output): int
    {
        var_dump($input->getOption('info'));
        if ($input->getOption('info')) {
            echo("1111111111111111");
        }
        echo("2222222222222222222");

        return Command::SUCCESS;
    }
}
