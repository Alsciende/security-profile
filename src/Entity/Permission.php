<?php

declare(strict_types=1);

namespace App\Entity;

use Assert\Assert;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\ORM\Mapping as ORM;

/**
 * Class Permission represents the state of a permission.
 * A permission is an authorization to do some action in the application.
 *
 * @ORM\Entity()
 * @ORM\Table(name="permissions")
 */
class Permission
{
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
     * @var Profile[]|ArrayCollection
     *
     * @ORM\ManyToMany(targetEntity="Profile", mappedBy="permissions")
     */
    private $profiles;

    /**
     * @var Token[]|ArrayCollection
     *
     * @ORM\ManyToMany(targetEntity="Token", mappedBy="permissions")
     */
    private $tokens;

    /**
     * Permission constructor.
     *
     * @param string $id
     * @param string $label
     */
    public function __construct(string $id, string $label)
    {
        Assert::that($id)->startsWith('ROLE_');
        $this->id = $id;
        $this->label = $label;
    }

    public function getId(): string
    {
        return $this->id;
    }

    public function getLabel(): string
    {
        return $this->label;
    }

    public function __toString()
    {
        return $this->id;
    }

    /**
     * @return Profile[]
     */
    public function getProfiles(): array
    {
        return $this->profiles->toArray();
    }

    /**
     * @return Token[]
     */
    public function getTokens(): array
    {
        return $this->tokens->toArray();
    }
}
