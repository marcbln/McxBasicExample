<?php

declare(strict_types=1);

namespace Mcx\BasicExample\Command;

use Shopware\Core\Framework\Adapter\Console\ShopwareStyle;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Input\InputOption;
use Symfony\Component\Console\Output\OutputInterface;

/**
 * 12/2022 created
 */
class MyFirstCommand extends Command
{
    protected static $defaultName = 'mcx:basic-example:my-first-command';

    private ShopwareStyle $io;

    public function configure(): void
    {
        $this->addOption('info');
        $this->addOption('batch-size', 'b', InputOption::VALUE_REQUIRED, 'Number of entities per iteration', '50');
    }

    public function run(InputInterface $input, OutputInterface $output): int
    {
        $this->io = new ShopwareStyle($input, $output);
        $this->io->success("hi!");

        return Command::SUCCESS;
    }

}
