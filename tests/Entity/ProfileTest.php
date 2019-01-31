<?php

declare(strict_types=1);

namespace App\Tests\Entity;

use App\Entity\Permission;
use App\Entity\Profile;
use PHPUnit\Framework\TestCase;

class ProfileTest extends TestCase
{
    public function testGetRole()
    {
        $profile = new Profile('id');
        $profile->setPermissions([
            new Permission('ROLE_CREATE_POST', 'User can create posts'),
            new Permission('ROLE_DELETE_POST', 'User can delete posts'),
        ]);

        $this->assertEquals(['ROLE_CREATE_POST', 'ROLE_DELETE_POST'], $profile->getRoles());
    }
}
