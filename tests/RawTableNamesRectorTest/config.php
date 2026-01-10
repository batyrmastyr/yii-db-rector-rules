<?php

declare(strict_types=1);

use Batyrmastyr\YiiDbRectorRules\Rules\RawTableNamesRector;
use Rector\Config\RectorConfig;

return static function (RectorConfig $rectorConfig): void {
    $rectorConfig->rule(RawTableNamesRector::class);
};