<?php

declare(strict_types=1);

namespace Batyrmastyr\YiiDbRectorRules\Rules;

use PhpParser\Comment\Doc;
use PhpParser\Node;
use PhpParser\Node\Expr\MethodCall;
use PhpParser\Node\Expr\PropertyFetch;
use PhpParser\Node\Identifier;
use PhpParser\Node\Name;
use PhpParser\Node\Name\FullyQualified;
use PhpParser\Node\Stmt\Use_;
use PHPStan\PhpDocParser\Ast\Type\IdentifierTypeNode;
use PHPStan\Type\ObjectType;
use Rector\BetterPhpDocParser\PhpDocInfo\PhpDocInfoFactory;
use Rector\CodingStyle\Node\NameImporter;
use Rector\PhpDocParser\PhpDocParser\PhpDocNodeTraverser;
use Rector\Rector\AbstractRector;
use Symplify\RuleDocGenerator\ValueObject\CodeSample\CodeSample;
use Symplify\RuleDocGenerator\ValueObject\RuleDefinition;

final class ReplaceIndexConstraintRector extends AbstractRector
{
    private const OLD_CLASS = 'Yiisoft\Db\Constraint\IndexConstraint';
    private const NEW_CLASS = 'Yiisoft\Db\Constraint\Index';
    private const OLD_SHORT_NAME = 'IndexConstraint';
    private const NEW_SHORT_NAME = 'Index';

    public function __construct(private PhpDocInfoFactory $phpDocInfoFactory,
                                private NameImporter      $nameImporter
    )
    {
    }

    public function getRuleDefinition(): RuleDefinition
    {
        return new RuleDefinition(
            'Replaces IndexConstraint from yiisoft/db:1.3.0 with Index from yiisoft/db:2.0.0',
            [
                new CodeSample(
                    <<<'CODE'
use Yiisoft\Db\Constraint\IndexConstraint;

/**
 * @param IndexConstraint[] $indexes
 */
function foo(IndexConstraint $index): void
{
}

function testVerifyTableIndexes($schema, $table): void
{
    /** @psalm-var IndexConstraint[] $indexes */
    $indexes = $schema->getTableIndexes($table, true);

    foreach ($indexes as $index) {
        $index->getColumnNames();
        $index->getName();
        $index->isPrimary();
        $index->isUnique();
    }
}
CODE
                    ,
                    <<<'CODE'
use Yiisoft\Db\Constraint\Index;

/**
 * @param Index[] $indexes
 */
function foo(Index $index): void
{
}

function testVerifyTableIndexes($schema, $table): void
{
    /** @psalm-var Index[] $indexes */
    $indexes = $schema->getTableIndexes($table, true);

    foreach ($indexes as $index) {
        $index->columnNames;
        $index->name;
        $index->isPrimary;
        $index->isUnique;
    }
}
CODE
                ),
            ]
        );
    }

    public function getNodeTypes(): array
    {
        return [
            Node\Stmt\Namespace_::class,
            Name::class,
            MethodCall::class,
            Node::class, // для PHPDoc
        ];
    }

    public function refactor(Node $node): ?Node
    {
        if ($node instanceof Use_) {
//            return null;
            return $this->appendToUseStatement($node);
        }

        if ($node instanceof Name) {
            return $this->replaceNames($node);

        }

        if ($node instanceof MethodCall) {
            return $this->replaceMethodCalls($node);
        }

        return $this->replaceInDocBlock($node);
    }

    /**
     * @param Use_ $node
     * @return Use_|null
     */
    public function appendToUseStatement(Use_ $node): ?Use_
    {
        // Обрабатываем только обычные use (не function, не const)
        if ($node->type !== Use_::TYPE_NORMAL) {
            return null;
        }

        if ($node->uses === []) {
            return null;
        }

        foreach ($node->uses as $use) {
            if ($use->name->toString() === self::OLD_CLASS) {
                $use->name = new FullyQualified(self::NEW_CLASS);

                return $node;
            }
        }

        return null;
    }

    private function replaceNames(Name $node): ?Name
    {
        /** 2. Замена IndexConstraint в коде на Index */
        if ($node instanceof FullyQualified) {
            if ($node->toString() === self::OLD_CLASS) {
                return new FullyQualified(self::NEW_CLASS);
            }

            return null;
        }

        // Замена короткого имени IndexConstraint на Index
        if ($node->toString() === self::OLD_SHORT_NAME) {
            return new Name(self::NEW_SHORT_NAME);
        }

        return null;
    }

    private function replaceMethodCalls(MethodCall $node): MethodCall|PropertyFetch|null
    {
        $methodName = $this->getName($node->name);

        // Маппинг методов на свойства
        $methodToPropertyMap = [
            'getColumnNames' => 'columnNames',
            'getName' => 'name',
            'isPrimary' => 'isPrimary',
            'isUnique' => 'isUnique',
        ];

        // Проверяем, что это один из методов, которые нужно заменить
        if ($methodName !== null && isset($methodToPropertyMap[$methodName])) {
            // Проверяем, что это вызов метода на объекте типа IndexConstraint/Index
            // Также заменяем, если тип не определен статически (может быть определен во время выполнения)
            if ($this->isObjectType($node->var, new ObjectType(self::OLD_CLASS))
                || $this->isObjectType($node->var, new ObjectType(self::NEW_CLASS))
            ) {
                return new PropertyFetch(
                    $node->var,
                    new Identifier($methodToPropertyMap[$methodName])
                );
            }
        }

        return null;
    }

    private function replaceInDocBlock(Node $node): ?Node
    {
        $phpDocInfo = $this->phpDocInfoFactory->createFromNode($node);
        if ($phpDocInfo === null) {
            return null;
        }

        $phpDocNode = $phpDocInfo->getPhpDocNode();

        $hasChanged = false;
        $phpDocNodeTraverser = new PhpDocNodeTraverser();

        $phpDocNodeTraverser->traverseWithCallable($phpDocNode, '', function ($node) use (&$hasChanged) {
            if ($node instanceof IdentifierTypeNode) {
                if ($node->name === self::OLD_SHORT_NAME) {
                    $node->name = '\\' . self::NEW_CLASS;
                    $hasChanged = true;

                    return $node;
                }
            }

            return null;
        });

        if ($hasChanged) {
            $node->setDocComment(new Doc((string)$phpDocNode));

            return $node;
        }

        return null;
    }
}

