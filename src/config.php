<?php
declare(strict_types=1);

namespace Batyrmastyr\YiiDbRectorRules;

use Batyrmastyr\YiiDbRectorRules\Rules\DsnUsageRector;
use Batyrmastyr\YiiDbRectorRules\Rules\RawTableNamesRector;
use Batyrmastyr\YiiDbRectorRules\Rules\RemoveInvalidArgumentExceptionRector;
use Batyrmastyr\YiiDbRectorRules\Rules\ReplaceIndexConstraintRector;
use Rector\Config\RectorConfig;
use Rector\Renaming\Rector\ClassConstFetch\RenameClassConstFetchRector;
use Rector\Renaming\ValueObject\RenameClassAndConstFetch;

return RectorConfig::configure()->withRules([
    RawTableNamesRector::class,
    ReplaceIndexConstraintRector::class,
    RemoveInvalidArgumentExceptionRector::class,
    DsnUsageRector::class,
])->withConfiguredRule(RenameClassConstFetchRector::class, [
    new RenameClassAndConstFetch('Yiisoft\Db\Schema\SchemaInterface', 'TYPE_BOOLEAN', 'Yiisoft\Db\Constant\ColumnType', 'BOOLEAN'),
    new RenameClassAndConstFetch('Yiisoft\Db\Schema\SchemaInterface', 'TYPE_BIT', 'Yiisoft\Db\Constant\ColumnType', 'BIT'),
    new RenameClassAndConstFetch('Yiisoft\Db\Schema\SchemaInterface', 'TYPE_TINYINT', 'Yiisoft\Db\Constant\ColumnType', 'TINYINT'),
    new RenameClassAndConstFetch('Yiisoft\Db\Schema\SchemaInterface', 'TYPE_SMALLINT', 'Yiisoft\Db\Constant\ColumnType', 'SMALLINT'),
    new RenameClassAndConstFetch('Yiisoft\Db\Schema\SchemaInterface', 'TYPE_INTEGER', 'Yiisoft\Db\Constant\ColumnType', 'INTEGER'),
    new RenameClassAndConstFetch('Yiisoft\Db\Schema\SchemaInterface', 'TYPE_BIGINT', 'Yiisoft\Db\Constant\ColumnType', 'BIGINT'),
    new RenameClassAndConstFetch('Yiisoft\Db\Schema\SchemaInterface', 'TYPE_FLOAT', 'Yiisoft\Db\Constant\ColumnType', 'FLOAT'),
    new RenameClassAndConstFetch('Yiisoft\Db\Schema\SchemaInterface', 'TYPE_DOUBLE', 'Yiisoft\Db\Constant\ColumnType', 'DOUBLE'),
    new RenameClassAndConstFetch('Yiisoft\Db\Schema\SchemaInterface', 'TYPE_DECIMAL', 'Yiisoft\Db\Constant\ColumnType', 'DECIMAL'),
    new RenameClassAndConstFetch('Yiisoft\Db\Schema\SchemaInterface', 'TYPE_MONEY', 'Yiisoft\Db\Constant\ColumnType', 'MONEY'),
    new RenameClassAndConstFetch('Yiisoft\Db\Schema\SchemaInterface', 'TYPE_CHAR', 'Yiisoft\Db\Constant\ColumnType', 'CHAR'),
    new RenameClassAndConstFetch('Yiisoft\Db\Schema\SchemaInterface', 'TYPE_STRING', 'Yiisoft\Db\Constant\ColumnType', 'STRING'),
    new RenameClassAndConstFetch('Yiisoft\Db\Schema\SchemaInterface', 'TYPE_TEXT', 'Yiisoft\Db\Constant\ColumnType', 'TEXT'),
    new RenameClassAndConstFetch('Yiisoft\Db\Schema\SchemaInterface', 'TYPE_BINARY', 'Yiisoft\Db\Constant\ColumnType', 'BINARY'),
    new RenameClassAndConstFetch('Yiisoft\Db\Schema\SchemaInterface', 'TYPE_UUID', 'Yiisoft\Db\Constant\ColumnType', 'UUID'),
    new RenameClassAndConstFetch('Yiisoft\Db\Schema\SchemaInterface', 'TYPE_TIMESTAMP', 'Yiisoft\Db\Constant\ColumnType', 'TIMESTAMP'),
    new RenameClassAndConstFetch('Yiisoft\Db\Schema\SchemaInterface', 'TYPE_DATETIME', 'Yiisoft\Db\Constant\ColumnType', 'DATETIME'),
    new RenameClassAndConstFetch('Yiisoft\Db\Schema\SchemaInterface', 'TYPE_DATETIMETZ', 'Yiisoft\Db\Constant\ColumnType', 'DATETIMETZ'),
    new RenameClassAndConstFetch('Yiisoft\Db\Schema\SchemaInterface', 'TYPE_TIME', 'Yiisoft\Db\Constant\ColumnType', 'TIME'),
    new RenameClassAndConstFetch('Yiisoft\Db\Schema\SchemaInterface', 'TYPE_TIMETZ', 'Yiisoft\Db\Constant\ColumnType', 'TIMETZ'),
    new RenameClassAndConstFetch('Yiisoft\Db\Schema\SchemaInterface', 'TYPE_ARRAY', 'Yiisoft\Db\Constant\ColumnType', 'ARRAY'),
    new RenameClassAndConstFetch('Yiisoft\Db\Schema\SchemaInterface', 'TYPE_STRUCTURED', 'Yiisoft\Db\Constant\ColumnType', 'STRUCTURED'),
    new RenameClassAndConstFetch('Yiisoft\Db\Schema\SchemaInterface', 'TYPE_JSON', 'Yiisoft\Db\Constant\ColumnType', 'JSON'),
    new RenameClassAndConstFetch('Yiisoft\Db\Schema\SchemaInterface', 'TYPE_ENUM', 'Yiisoft\Db\Constant\ColumnType', 'ENUM')
]);