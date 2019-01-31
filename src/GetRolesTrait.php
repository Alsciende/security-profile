<?php

declare(strict_types=1);

namespace App;

use App\Entity\Permission;

trait GetRolesTrait
{
    public function getRoles()
    {
        return array_map(function (Permission $permission) {
            return $permission->getRole();
        }, $this->getPermissions());
    }
}
