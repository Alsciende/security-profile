<?php

declare(strict_types=1);

namespace App\Entity;

use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Security\Core\User\UserInterface;

/**
 * Class User represents the state of a user.
 * A user is a person who registered and can use the application via a token.
 *
 * @ORM\Entity()
 * @ORM\Table(name="users")
 */
class User implements UserInterface
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
    private $username;

    /**
     * @var string
     *
     * @ORM\Column(type="string")
     */
    private $password;

    /**
     * @var Profile
     *
     * @ORM\ManyToOne(targetEntity="Profile", inversedBy="users")
     */
    private $profile;

    /**
     * @var Token[]|ArrayCollection
     *
     * @ORM\OneToMany(targetEntity="Token", mappedBy="user")
     */
    private $tokens;

    public function __construct(string $id)
    {
        $this->id = $id;
    }

    /**
     * @param string $username
     */
    public function setUsername(string $username): void
    {
        $this->username = $username;
    }

    /**
     * @param string $password
     */
    public function setPassword(string $password): void
    {
        $this->password = $password;
    }

    /**
     * @param Profile $profile
     */
    public function setProfile(Profile $profile): void
    {
        $this->profile = $profile;
    }

    /**
     * @return string[]
     */
    public function getRoles()
    {
        return $this->profile->getRoles();
    }

    /**
     * @return Profile
     */
    public function getProfile(): Profile
    {
        return $this->profile;
    }

    /**
     * @return string
     */
    public function getId(): string
    {
        return $this->id;
    }

    public function getPassword()
    {
        return $this->password;
    }

    public function getSalt()
    {
        return '';
    }

    public function getUsername()
    {
        return $this->username;
    }

    public function eraseCredentials()
    {
    }

    /**
     * @return Token[]
     */
    public function getTokens(): array
    {
        return $this->tokens->toArray();
    }
}
