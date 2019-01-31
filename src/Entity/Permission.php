<?php

declare(strict_types=1);

namespace App\Entity;

class Permission
{
    /**
     * @var string
     */
    private $role;

    /**
     * @var string
     */
    private $label;

    /**
     * Permission constructor.
     *
     * @param string $role
     * @param string $label
     */
    public function __construct(string $role, string $label)
    {
        $this->role = $role;
        $this->label = $label;
    }

    public function getRole(): string
    {
        return $this->role;
    }

    public function getLabel(): string
    {
        return $this->label;
    }
}
