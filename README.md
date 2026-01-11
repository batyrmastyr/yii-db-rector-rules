Several Rector rules to upgrade yiisoft/db package from 1.3 to 2.0, mostly for tests.

## Install
```shell
composer require --dev batyrmastyr/yii-db-rector-rules
```

## How to use
Add rule set to `rector.php` like below
```php
return RectorConfig::configure()
    ->withSets([YiisoftDbUpgradeSet::Yii3DbV2])
;
```

## What's inside
- Remove `InvalidArgumentException`
- Replace `->getSchema->getRawTableName()` with `->getQuoter()->getRawTableName()`
- Replace `SchemaInterface::TYPE_*` constants with `ColumnType::*`
- Replace `Yiisoft\Db\Constraint\IndexConstraint` with `Yiisoft\Db\Constraint\Index` and change method calls to property access, e.g. `$index->getColumnNames()[0]` -> `$index->columnNames[0]`  