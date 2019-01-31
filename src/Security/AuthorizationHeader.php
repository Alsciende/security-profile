<?php

declare(strict_types=1);

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
        $header = $bag->get(self::HEADER);
        if (is_null($header)) {
            throw new \Exception('Missing header ' . self::HEADER);
        }
        if (is_array($header)) {
            throw new \Exception('Too many headers ' . self::HEADER);
        }

        $fields = explode(' ', $header, 2);

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
