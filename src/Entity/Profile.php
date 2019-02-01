<?php

declare(strict_types=1);

namespace App\Entity;

use App\GetRolesTrait;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\ORM\Mapping as ORM;

/**
 * Class Profile represents the state of a profile.
 * A profile is the set of authorizations that a group of users have in the application.
 *
 * @ORM\Entity()
 * @ORM\Table(name="profiles")
 */
class Profile
{
    use GetRolesTrait;

    /**
     * @var string
     *
     * @ORM\Id()
     * @ORM\Column(type="string")
     */
    private $id;

    /**
     * @var string
     *
     * @ORM\Column(type="string")
     */
    private $label;

    /**
     * @var Permission[]|ArrayCollection
     *
     * @ORM\ManyToMany(targetEntity="Permission", inversedBy="profiles")
     * @ORM\JoinTable(name="profile_permissions",
     *     joinColumns={@ORM\JoinColumn(name="profile_id", referencedColumnName="id")},
     *     inverseJoinColumns={@ORM\JoinColumn(name="permission_id", referencedColumnName="id")}
     *     )
     */
    private $permissions;

    /**
     * @var User[]|ArrayCollection
     *
     * @ORM\OneToMany(targetEntity="User", mappedBy="profile")
     */
    private $users;

    /**
     * Profile constructor.
     *
     * @param string $id
     */
    public function __construct(string $id)
    {
        $this->id = $id;
    }

    public function getId(): string
    {
        return $this->id;
    }

    public function getLabel(): string
    {
        return $this->label;
    }

    public function setLabel(string $label): self
    {
        $this->label = $label;

        return $this;
    }

    /**
     * @return Permission[]
     */
    public function getPermissions(): array
    {
        return $this->permissions->toArray();
    }

    /**
     * @param Permission[] $permissions
     */
    public function setPermissions(array $permissions): void
    {
        $this->clearPermissions();
        foreach ($permissions as $permission) {
            $this->addPermission($permission);
        }
    }

    public function clearPermissions()
    {
        $this->permissions = new ArrayCollection();
    }

    public function addPermission(Permission $permission)
    {
        $this->permissions[] = $permission;
    }

    /**
     * @return User[]
     */
    public function getUsers(): array
    {
        return $this->users->toArray();
    }
}
