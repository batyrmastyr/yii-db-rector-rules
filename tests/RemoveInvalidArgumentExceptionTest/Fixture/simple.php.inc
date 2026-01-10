<?php

namespace Something;

use Yiisoft\Db\Exception\InvalidArgumentException;

/**
 * @throws InvalidArgumentException
 */
function foo()
{
}

?>
-----
<?php

namespace Something;

function foo()
{
}

?>
