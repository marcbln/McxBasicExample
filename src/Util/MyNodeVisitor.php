<?php declare(strict_types=1);

namespace Mcx\BasicExample\Util;


use PhpParser\Node;
use PhpParser\Node\Expr\ClassConstFetch;
use PhpParser\Node\Stmt\ClassConst;
use PhpParser\NodeVisitorAbstract;
use WhyooOs\Util\UtilDebug;

class MyNodeVisitor extends NodeVisitorAbstract
{
    const NAMESPACE_SEPARATOR = '\\';

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
                if (empty($alias)) {
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
            // ---- extract event class from docblock
            $comment = $node->getDocComment()?->getText() ?? '';
            $comment = str_replace("\n", " ", $comment);
            if (!preg_match('#@Event\((.*)\)#', $comment, $matches)) {
                return;
            }
            $eventClass = trim($matches[1], "\n\r \"'\\");

            $constName = $node->consts[0]->name->name;

            UtilDebug::d($this->currentFile, $constName);

            $constValue = $this->_getClassConstValue($node->consts[0]->value);

            $this->found[] = [
                // 'constDocBlock' => $constDocBlock,
                'eventClass' => $eventClass,
                'constName'  => $constName,
                'constValue' => $constValue,
                'file'       => $this->currentFile,
                'classFQCN'  => $this->currentNamespace . self::NAMESPACE_SEPARATOR . $this->currentClassname,
            ];
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

    private function _getClassConstValue(/*\PhpParser\Node\Expr\ClassConstFetch | \PhpParser\Node\Scalar\String_*/ $val)
    {
        if ($val instanceof \PhpParser\Node\Scalar\String_) {
            return $val->getAttribute('rawValue');
        }

        if ($val instanceof ClassConstFetch) {
            // UtilDebug::dd($val);
            if (count($val->class->parts) === 1) {
                $cls = $val->class->parts[0];
                if (array_key_exists($cls, $this->useMap)) {
                    $classFQN = $this->useMap[$cls];
                } else {
                    $classFQN = $this->currentNamespace . self::NAMESPACE_SEPARATOR . $cls;
                }
                if($val->name->name === 'class') {
                    return $classFQN;
                }
                return constant("$classFQN::{$val->name->name}");
            } else {
                return "ZZZZZ " . implode(self::NAMESPACE_SEPARATOR, $val->class->parts[0]);
            }
        } else {
            UtilDebug::dd($val);
            throw new \Exception("type fail " . get_class($val));
        }
        UtilDebug::dd($val);
    }
}

