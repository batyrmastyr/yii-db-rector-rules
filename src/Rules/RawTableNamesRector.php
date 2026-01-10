<?php

declare(strict_types=1);

namespace Batyrmastyr\YiiDbRectorRules\Rules;

use PhpParser\Node;
use PhpParser\Node\Expr\MethodCall;
use PhpParser\Node\Identifier;
use Rector\Rector\AbstractRector;
use Symplify\RuleDocGenerator\ValueObject\CodeSample\CodeSample;
use Symplify\RuleDocGenerator\ValueObject\RuleDefinition;

final class RawTableNamesRector extends AbstractRector
{
    public function getRuleDefinition(): RuleDefinition
    {
        return new RuleDefinition(
            'Fixes SchemaInterface::getRawTableName() calls',
            [
                new CodeSample(
                    <<<'CODE'
function foo(ConnectionInterface $connection, string $table)
{
    return $connection->getSchema()->getRawTableName($table);
}
CODE
                    ,
                    <<<'CODE'
function foo(ConnectionInterface $schema, string $table)
{
    return $connection->getQuoter()->getRawTableName($table);
}
CODE
                ),
            ]
        );
    }

    public function getNodeTypes(): array
    {
        return [
            MethodCall::class,
        ];
    }

    public function refactor(Node $node): ?Node
    {
        assert($node instanceof MethodCall);
        if (! $this->isName($node->name, 'getRawTableName')) {
            return null;
        }

        if ($node->var instanceof MethodCall && $this->isName($node->var->name, 'getSchema')) {
            return new MethodCall(
                new MethodCall(
                    $node->var->var,
                    new Identifier('getQuoter')
                ),
                new Identifier('getRawTableName'),
                $node->args
            );
        }

        return null;
    }
}
