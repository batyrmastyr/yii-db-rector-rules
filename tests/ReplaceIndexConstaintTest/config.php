<?php

declare(strict_types=1);

use Batyrmastyr\YiiDbRectorRules\Rules\ReplaceIndexConstraintRector;
use Rector\Config\RectorConfig;

return static function (RectorConfig $rectorConfig): void {
    $rectorConfig->rule(ReplaceIndexConstraintRector::class);
};