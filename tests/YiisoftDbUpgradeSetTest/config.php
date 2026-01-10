<?php

declare(strict_types=1);

use Batyrmastyr\YiiDbRectorRules\YiisoftDbUpgradeSet;
use Rector\Config\RectorConfig;

return RectorConfig::configure()
    ->withSets([YiisoftDbUpgradeSet::Yii3DbV2])
;