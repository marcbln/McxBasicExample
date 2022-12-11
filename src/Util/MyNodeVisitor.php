<?php declare(strict_types=1);

namespace Mcx\BasicExample\Util;


use PhpParser\Node;
use PhpParser\Node\Stmt\ClassConst;
use PhpParser\NodeVisitorAbstract;
use WhyooOs\Util\UtilDebug;

class MyNodeVisitor extends NodeVisitorAbstract
{
    const NAMESPACE_SEPARATOR = '.';

    public array $found = [];
    private string $currentFile; // set in $this->reset($pathFile)
    private ?string $currentNamespace; // set to null in $this->reset()
    private ?string $currentClassname; // set to null in $this->reset()
    private array $useMap; // assoc array, set to [] in $this->reset()

    public function enterNode(Node $node)
    {
        // ---- namespace declaration (at top of a file)
        if ($node instanceof \PhpParser\Node\Stmt\Namespace_) {
            $this->currentNamespace = implode(self::NAMESPACE_SEPARATOR, $node->name->parts);
        }

        // ---- "use" keyword (at top of a file)
        if ($node instanceof \PhpParser\Node\Stmt\Use_) {
            foreach ($node->uses as $use) {
                $alias = $use->alias;
                if(empty($alias)) {
                    $alias = $use->name->parts[count($use->name->parts) - 1];
                }
                $this->useMap[$alias] = implode(self::NAMESPACE_SEPARATOR, $use->name->parts);
                // UtilDebug::dd($use, $this->useMap);
            }
        }

        // ---- "class" keyword
        if ($node instanceof \PhpParser\Node\Stmt\Class_) {
            // UtilDebug::d(basename($this->currentFile), $node->name->name);
            $this->currentClassname = $node->name->name;  // without namespace "eg customer event"
        }


        if ($node instanceof ClassConst) {
            $constDocBlock = $node->getDocComment()->getText();
            $constName = $node->consts[0]->name->name;


            $constValue = $node->consts[0]->value->getAttribute('rawValue');
            if (empty($constValue)) {
                // example: public const ORDER_PAYMENT_METHOD_CHANGED = OrderPaymentMethodChangedEvent::EVENT_NAME;
                // $constValue = $
                //  UtilDebug::dd($node->consts[0]->value);
            }

            $this->found[] = [
                'constDocBlock' => $constDocBlock,
                'constName'     => $constName,
                'constValue'    => $constValue,
                'file'          => $this->currentFile,
                'fqcn'          => $this->currentNamespace . self::NAMESPACE_SEPARATOR . $this->currentClassname,
            ];
//            @$GLOBALS['FFF'][$constName] ++;
//            @$GLOBALS['VVV'][$constValue] ++;
//            @$GLOBALS['DDD'][$constDocBlock] ++;
            if (empty($constValue)) {
                if ($node->consts[0]->value instanceof \PhpParser\Node\Expr\ClassConstFetch) {
                    UtilDebug::dd("FAIL", $this->found[count($this->found) - 1], $node, $this->useMap, "FAIL");
                }
            }
        }
    }


    /**
     * called when processing a new file
     *
     * @param string $pathFile
     * @return void
     */
    public function reset(string $pathFile)
    {
        $this->currentFile = $pathFile;
        $this->currentNamespace = null;
        $this->currentClassname = null;
        $this->useMap = [];
    }
}

