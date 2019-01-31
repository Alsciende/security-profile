<?php

declare(strict_types=1);

namespace App\Entity;

use App\GetRolesTrait;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Security\Core\User\UserInterface;

/**
 * Class Token represents the state of an authentication token.
 * A token is like a user but with a subset of its permissions.
 * It is the only way for a user to authenticate when using the application.
 *
 * @ORM\Entity()
 * @ORM\Table(name="tokens")
 */
class Token implements UserInterface
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
     * @var UserInterface
     *
     * @ORM\ManyToOne(targetEntity="User", inversedBy="tokens")
     */
    private $user;

    /**
     * @var Permission[]|ArrayCollection
     *
     * @ORM\ManyToMany(targetEntity="Permission", inversedBy="tokens")
     * @ORM\JoinTable(name="token_permissions",
     *     joinColumns={@ORM\JoinColumn(name="token_id", referencedColumnName="id")},
     *     inverseJoinColumns={@ORM\JoinColumn(name="permission_id", referencedColumnName="id")}
     *     )
     */
    private $permissions;

    /**
     * Token constructor.
     *
     * @param string        $id
     * @param UserInterface $user
     * @param Permission[]  $permissions
     */
    public function __construct(string $id, UserInterface $user, array $permissions)
    {
        $this->id = $id;
        $this->user = $user;

        foreach ($permissions as $permission) {
            $this->addPermission($permission);
        }
    }

    public function addPermission(Permission $permission)
    {
        $this->permissions[] = $permission;
    }

    public function getId(): string
    {
        return $this->id;
    }

    public function getUser(): UserInterface
    {
        return $this->user;
    }

    public function getPassword()
    {
        return $this->user->getPassword();
    }

    public function getSalt()
    {
        return $this->user->getSalt();
    }

    public function getUsername()
    {
        return $this->user->getUsername();
    }

    public function eraseCredentials()
    {
        return $this->user->eraseCredentials();
    }

    /**
     * @return Permission[]
     */
    public function getPermissions()
    {
        return $this->permissions->toArray();
    }
}
