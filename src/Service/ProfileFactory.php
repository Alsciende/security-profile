<?php

declare(strict_types=1);

namespace App\Service;

use App\Entity\Profile;

/**
 * Creates Profile instances.
 */
class ProfileFactory
{
    /**
     * Duplicate a Profile as a new Profile with the same label and permissions.
     *
     * @param Profile $profile
     * @param string  $id
     *
     * @return Profile
     */
    public function duplicateProfile(string $id, Profile $profile): Profile
    {
        $duplicate = new Profile($id);
        $duplicate->setLabel($profile->getLabel());
        $duplicate->setPermissions($profile->getPermissions());

        return $duplicate;
    }
}
