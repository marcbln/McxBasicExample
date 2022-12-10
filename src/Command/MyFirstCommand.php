<?php

declare(strict_types=1);

namespace Mcx\BasicExample\Command;

use Exception;
use Mcx\BasicExample\Util\UtilCmd;
use PhpParser\Error;
use PhpParser\Node;
use PhpParser\Node\Stmt\ClassConst;
use PhpParser\NodeDumper;
use PhpParser\NodeTraverser;
use PhpParser\NodeVisitorAbstract;
use PhpParser\ParserFactory;
use Shopware\Core\Checkout\Order\Event\OrderPaymentMethodChangedEvent;
use Shopware\Core\Kernel;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Input\InputOption;
use Symfony\Component\Console\Output\OutputInterface;
use WhyooOs\Util\Arr\UtilStringArray;
use WhyooOs\Util\UtilDebug;


class MyVisitor extends NodeVisitorAbstract
{
    public array $found = [];
    public string $currentFile;

    public function enterNode(Node $node)
    {
        if ($node instanceof ClassConst) {
            $constDocBlock = $node->getDocComment()->getText();
            $constName = $node->consts[0]->name->name;


            $constValue = $node->consts[0]->value->getAttribute('rawValue');
            if(empty($constValue)) {
                // example: public const ORDER_PAYMENT_METHOD_CHANGED = OrderPaymentMethodChangedEvent::EVENT_NAME;
                // $constValue = $
               //  UtilDebug::dd($node->consts[0]->value);
            }

            $this->found[] = [
                'constDocBlock' => $constDocBlock,
                'constName'     => $constName,
                'constValue'    => $constValue,
                'file'          => $this->currentFile,
            ];
//            @$GLOBALS['FFF'][$constName] ++;
//            @$GLOBALS['VVV'][$constValue] ++;
//            @$GLOBALS['DDD'][$constDocBlock] ++;
            if(empty($constValue)) {
                if( $node->consts[0]->value instanceof \PhpParser\Node\Expr\ClassConstFetch) {
                    UtilDebug::dd($this->found, $node);
                }
            }
        }
    }
}


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
        $files = $this->_findAllFilesWithEvents([__FILE__]);
        $dumper = new NodeDumper;
        $traverser = new NodeTraverser();
        $myVisitor = new MyVisitor();
        $traverser->addVisitor($myVisitor);

        foreach ($files as $pathFile) {
            echo "\n\n\n=====================================\n$pathFile\n=====================================\n\n\n";
            $ast = $this->_parseFile($pathFile);

            $myVisitor->currentFile = $pathFile;
            $traverser->traverse($ast);
        }

        UtilDebug::dd($myVisitor->found, $GLOBALS['FFF'], $GLOBALS['DDD'], $GLOBALS['VVV']);
        UtilDebug::dd("sdjfjkhsdjkfjksdhjahfkjhkjfasdhfjk");

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
