<?php

namespace Litvinab\Bundle\RestApiTestBundle\Service;

use Symfony\Component\HttpFoundation\Request;

class RequestAccessChecker
{
    private const HEADER_KEY = 'Rest-API-Test-Access-Token';

    /**
     * @var bool
     */
    private $turnedOn;

    /**
     * Access token
     *
     * @var string
     */
    private $accessToken;

    /**
     * RequestAccessChecker constructor.
     * @param bool $turnedOn
     * @param string $accessToken
     */
    public function __construct(bool $turnedOn, string $accessToken)
    {
        $this->turnedOn = $turnedOn;
        $this->accessToken = $accessToken;
    }

    /**
     * Check is access granted
     *
     * @param Request $request
     *
     * @return bool
     */
    public function isAccessGranted(Request $request)
    {
        $response = false;
        if ($this->turnedOn && $request->headers->get(self::HEADER_KEY) === $this->accessToken) {
            $response = true;
        }

        return $response;
    }
}