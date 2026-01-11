<?php

declare(strict_types=1);

namespace Batyrmastyr\YiiDbRectorRules\Rules;

use PhpParser\Node;
use PhpParser\Node\Expr\MethodCall;
use PhpParser\Node\Identifier;
use PHPStan\Type\ObjectType;
use Rector\Rector\AbstractRector;
use Symplify\RuleDocGenerator\ValueObject\CodeSample\CodeSample;
use Symplify\RuleDocGenerator\ValueObject\RuleDefinition;

final class DsnUsageRector extends AbstractRector
{
    private const CLASSES = [
        'Yiisoft\Db\Mssql\Dsn',
        'Yiisoft\Db\Mysql\Dsn',
        'Yiisoft\Db\Pgsql\Dsn',
        'Yiisoft\Db\Sqlite\Dsn',
        'Yiisoft\Db\Oracle\Dsn',
    ];

    public function getRuleDefinition(): RuleDefinition
    {
        return new RuleDefinition(
            'Removes call to toString() on Dsn objects as they are Stringable',
            [
                new CodeSample(
                    <<<'CODE'
$pdoDriver = new Driver((new Dsn('sqlite', __DIR__ . '/runtime/yiitest.sq3'))->asString());
CODE
                    ,
                    <<<'CODE'
$pdoDriver = new Driver(new Dsn('sqlite', __DIR__ . '/runtime/yiitest.sq3'));
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
        if (! $this->isName($node->name, 'asString')) {
            return null;
        }

        if (!($node->var instanceof Node\Expr\New_)) {
            return null;
        }


        foreach (self::CLASSES as $class) {
            if ($this->isObjectType($node->var->class, new ObjectType($class))) {
                return $node->var;
            }
        }

        return null;
    }
}
