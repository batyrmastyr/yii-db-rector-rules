<?php

declare(strict_types=1);

namespace Batyrmastyr\YiiDbRectorRules\Rules;

use PhpParser\Node;
use PhpParser\Node\Stmt\Namespace_;
use PhpParser\Node\Stmt\Use_;
use PHPStan\PhpDocParser\Ast\Type\IdentifierTypeNode;
use Rector\BetterPhpDocParser\PhpDocInfo\PhpDocInfoFactory;
use Rector\BetterPhpDocParser\PhpDocManipulator\PhpDocTagRemover;
use Rector\Comments\NodeDocBlock\DocBlockUpdater;
use Rector\Rector\AbstractRector;
use Symplify\RuleDocGenerator\ValueObject\CodeSample\CodeSample;
use Symplify\RuleDocGenerator\ValueObject\RuleDefinition;

final class RemoveInvalidArgumentExceptionRector extends AbstractRector
{
    private const EXCEPTION_FQCN  = 'Yiisoft\Db\Exception\InvalidArgumentException';
    private const EXCEPTION_SHORT = 'InvalidArgumentException';

    public function __construct(private PhpDocInfoFactory $phpDocInfoFactory, private PhpDocTagRemover $phpDocTagRemover, private DocBlockUpdater $docBlockUpdater)
    {
    }

    public function getRuleDefinition(): RuleDefinition
    {
        return new RuleDefinition(
            'Removes Yiisoft Db InvalidArgumentException from use statements, code and PHPDoc',
            [
                new CodeSample(
                    <<<'CODE'
use Yiisoft\Db\Exception\InvalidArgumentException;

/**
 * @throws InvalidArgumentException
 */
function foo()
{
}
CODE
                    ,
                    <<<'CODE'
function foo()
{
}
CODE
                ),
            ]
        );
    }

    public function getNodeTypes(): array
    {
        return [
            Namespace_::class,
            Node\Name::class,
            Node::class, // для PHPDoc
        ];
    }

    public function refactor(Node $node): ?Node
    {
        if ($node instanceof Namespace_) {
            return $this->removeFromUses($node);
        }

        return $this->removeFromThrows($node);
    }

    public function removeFromThrows(Node $node): ?Node
    {
        $phpDocInfo = $this->phpDocInfoFactory->createFromNode($node);
        if ($phpDocInfo === null) {
            return null;
        }

        $phpDocNode = $phpDocInfo->getPhpDocNode();

        // Получаем все @throws теги
        $throwsTags = $phpDocNode->getTagsByName('@throws');

        if (count($throwsTags) > 0) {
            $changed = false;

            foreach ($throwsTags as $throwsTag) {
                $throwsTagValue = $throwsTag->value;
                $typeNode = $throwsTagValue->type;

                // Проверяем, является ли тип IdentifierTypeNode с нужным именем
                if ($typeNode instanceof IdentifierTypeNode
                    && in_array($typeNode->name, [self::EXCEPTION_SHORT, self::EXCEPTION_FQCN], true)
                ) {
                    $changed = $this->phpDocTagRemover->removeTagValueFromNode($phpDocInfo, $throwsTag);
                }
            }

            if ($changed) {
                $this->docBlockUpdater->updateRefactoredNodeWithPhpDocInfo($node);
                return $node;
            }
        }

        return null;
    }

    /**
     * @param Namespace_ $node
     * @return Namespace_|null
     */
    public function removeFromUses(Namespace_ $node): ?Namespace_
    {
        foreach ($node->stmts as $idx => $stmt) {
            if ($stmt instanceof Use_ && $this->isName($stmt, self::EXCEPTION_FQCN)) {
                unset($node->stmts[$idx]);

                return $node;
            }
        }

        return null;
    }
}
