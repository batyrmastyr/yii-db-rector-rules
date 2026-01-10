<?php
declare(strict_types=1);

namespace Batyrmastyr\YiiDbRectorRules;

use Rector\Set\Contract\SetProviderInterface;
use Rector\Set\ValueObject\ComposerTriggeredSet;

class YiisoftDbUpgradeSet implements SetProviderInterface
{
    public const Yii3DbV2 = __DIR__ . '/config.php';

    public function provide(): array
    {
        return [
            new ComposerTriggeredSet(self::Yii3DbV2, 'yiisoft/db', '2.0', __DIR__ . '/config.php')
        ];
    }
}
