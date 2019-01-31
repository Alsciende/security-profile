<?php

declare(strict_types=1);

namespace App\Exception;

use App\Entity\Permission;

class CannotGrantTokenException extends \Exception
{
    public function __construct(Permission $permission)
    {
        parent::__construct(sprintf(
            'Cannot grant role [%s] to token.',
            $permission->getId()
        ));
    }
}
