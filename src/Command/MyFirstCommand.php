<?php

declare(strict_types=1);

namespace Mcx\BasicExample\Command;

use Exception;
use Mcx\BasicExample\Util\MyNodeVisitor;
use Mcx\BasicExample\Util\UtilCmd;
use PhpParser\Error;
use PhpParser\NodeDumper;
use PhpParser\NodeTraverser;
use PhpParser\ParserFactory;
use Shopware\Core\Framework\Adapter\Console\ShopwareStyle;
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
    private ShopwareStyle $io;

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
        $this->io = new ShopwareStyle($input, $output);

        $files = $this->_findAllFilesWithEvents([__FILE__]);
        $traverser = new NodeTraverser();
        $myVisitor = new MyNodeVisitor();
        $traverser->addVisitor($myVisitor);

        foreach ($files as $pathFile) {
            $this->io->info("==== $pathFile");

            $ast = $this->_parseFile($pathFile);

            $myVisitor->reset($pathFile);
            $traverser->traverse($ast);
            UtilDebug::d($myVisitor);
        }

//        UtilDebug::dd($myVisitor->found, $GLOBALS['FFF'], $GLOBALS['DDD'], $GLOBALS['VVV']);
//        UtilDebug::dd("sdjfjkhsdjkfjksdhjahfkjhkjfasdhfjk");

        return Command::SUCCESS;
    }

    /**
     * 12/2022 created
     *
     * @return string[]
     * @throws Exception
     */
    private function _findAllFilesWithEvents(array $excludes = []): array
    {
        $ret = UtilCmd::exec([
            'grep',
            '-lIrF',
            '--include', '*.php',
            '@Event',
            $this->kernel->getProjectDir(),
        ]);

        $files = UtilStringArray::trimExplode("\n", $ret);

        return array_diff($files, $excludes);
    }

    /**
     * private helper
     * 12/2022 created
     *
     * @param string $pathFile
     */
    private function _parseFile(string $pathFile)
    {
        $parser = (new ParserFactory)->create(ParserFactory::PREFER_PHP7);
        $code = file_get_contents($pathFile);
        try {
            return $parser->parse($code);
        } catch (Error $error) {
            echo "Parse error: {$error->getMessage()}\n";
            return null;
        }
    }
}
