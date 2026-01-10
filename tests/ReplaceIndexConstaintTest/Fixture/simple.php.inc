<?php

use \Yiisoft\Db\Constraint\IndexConstraint;

/**
 * @param IndexConstraint[] $indexes
 */
function foo(IndexConstraint $index): void
{
}

function testVerifyTableIndexes($schema, $table): void
{
    /**
     * @psalm-var IndexConstraint[] $indexes
     * @var IndexConstraint $index
     */
    $indexes = $schema->getTableIndexes($table, true);

    foreach ($indexes as $index) {
        $index->getColumnNames();
        $index->getName();
        $index->isPrimary();
        $index->isUnique();
    }

    $indexes[0]->getColumnNames();
}
?>
-----
<?php

use \Yiisoft\Db\Constraint\Index;

/**
 * @param \Yiisoft\Db\Constraint\Index[] $indexes
 */
function foo(\Yiisoft\Db\Constraint\Index $index): void
{
}

function testVerifyTableIndexes($schema, $table): void
{
    /**
     * @psalm-var \Yiisoft\Db\Constraint\Index[] $indexes
     * @var \Yiisoft\Db\Constraint\Index $index
     */
    $indexes = $schema->getTableIndexes($table, true);

    foreach ($indexes as $index) {
        $index->columnNames;
        $index->name;
        $index->isPrimary;
        $index->isUnique;
    }

    $indexes[0]->columnNames;
}
?>
