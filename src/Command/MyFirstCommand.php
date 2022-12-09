<?php

declare(strict_types=1);

namespace Mcx\BasicExample\Command;

use Mcx\BasicExample\Util\UtilCmd;
use Shopware\Core\Kernel;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Input\InputOption;
use Symfony\Component\Console\Output\OutputInterface;
use WhyooOs\Util\Arr\UtilStringArray;
use WhyooOs\Util\UtilDebug;

class MyFirstCommand extends Command
{
    protected static $defaultName = 'mcx:basic-example:my-first-command';

    private Kernel $kernel; // used for getting project dir

    public function __construct(Kernel $kernel, string $name = null)
    {
        parent::__construct($name);
        $this->kernel = $kernel;
    }


    public function configure(): void
    {
        $this->addOption('info');
        $this->addOption('batch-size', 'b', InputOption::VALUE_REQUIRED, 'Number of entities per iteration', '50');
    }

    public function run(InputInterface $input, OutputInterface $output): int
    {

        $files = $this->findAllFilesWithEvents();
        UtilDebug::dd($files);

        return Command::SUCCESS;
    }

    /**
     * 12/2022 created
     *
     * @return string[]
     * @throws \Exception
     */
    public function findAllFilesWithEvents(): array
    {
        $ret = UtilCmd::exec([
            'grep',
            '-lIrF',
            '--include', '*.php',
            '@Event',
            $this->kernel->getProjectDir(),
        ]);

        return UtilStringArray::trimExplode("\n", $ret);
    }
}
