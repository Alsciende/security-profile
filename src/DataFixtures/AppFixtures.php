<?php

declare(strict_types=1);

namespace App\DataFixtures;

use App\Entity\Profile;
use App\Entity\Permission;
use App\Entity\Token;
use App\Entity\User;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Common\Persistence\ObjectManager;

class AppFixtures extends Fixture
{
    public function load(ObjectManager $manager)
    {
        $permissionView = new Permission('ROLE_VIEW', 'can view stuff');
        $permissionEdit = new Permission('ROLE_EDIT', 'can edit stuff');

        $profileVisitor = new Profile('Visitor');
        $profileVisitor->setLabel('Visitor');
        $profileVisitor->setPermissions([$permissionView]);

        $profileEditor = new Profile('Editor');
        $profileEditor->setLabel('Editor');
        $profileEditor->setPermissions([$permissionView, $permissionEdit]);

        $user = new User('test');
        $user->setUsername('test@example.com');
        $user->setPassword('');
        $user->setProfile($profileEditor);

        $tokenFull = new Token('full', $user, $user->getProfile()->getPermissions());
        $tokenPartial = new Token('partial', $user, [$permissionView]);

        $manager->persist($permissionView);
        $manager->persist($permissionEdit);
        $manager->persist($profileVisitor);
        $manager->persist($profileEditor);
        $manager->persist($user);
        $manager->persist($tokenFull);
        $manager->persist($tokenPartial);

        $manager->flush();
    }
}
