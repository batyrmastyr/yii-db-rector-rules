<?php

declare(strict_types=1);

namespace Something;

use Yiisoft\Db\Connection\ConnectionInterface;

/**
 * Manages the log table schema in the database.
 */
final class DbSchemaManager
{
    public function __construct(private readonly ConnectionInterface $db)
    {
    }

    public function ensureTable(string $table = '{{%yii_log}}'): void
    {
        $tableRawName = $this->db->getSchema()->getRawTableName($table);
    }
}
?>
-----
<?php

declare(strict_types=1);

namespace Something;

use Yiisoft\Db\Connection\ConnectionInterface;

/**
 * Manages the log table schema in the database.
 */
final class DbSchemaManager
{
    public function __construct(private readonly ConnectionInterface $db)
    {
    }

    public function ensureTable(string $table = '{{%yii_log}}'): void
    {
        $tableRawName = $this->db->getQuoter()->getRawTableName($table);
    }
}
?>
