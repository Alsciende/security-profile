<?php

namespace App\Security;

use Symfony\Component\HttpFoundation\HeaderBag;

class AuthorizationHeader
{
    const HEADER = 'Authorization';

    /**
     * @var string
     */
    private $scheme;

    /**
     * @var string
     */
    private $credentials;

    public function __construct(HeaderBag $bag)
    {
        $fields = explode(' ', $bag->get(self::HEADER, ''), 2);

        $this->scheme = $fields[0] ?? '';
        $this->credentials = $fields[1] ?? '';
    }

    public function getScheme(): string
    {
        return $this->scheme;
    }

    public function getCredentials(): string
    {
        return $this->credentials;
    }
}