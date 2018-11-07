<?php

/*
 * This file is part of the `informaticauco/oauth2-client`.
 *
 * Copyright (C) 2018 by Sergio Gómez <sergio@uco.es>
 *
 * This code was developed by Universidad de Córdoba (UCO https://www.uco.es)
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Tests\Uco\OAuth2\Client\Provider;

use PHPUnit\Framework\TestCase;
use Uco\OAuth2\Client\Provider\Uco;

class UcoTest extends TestCase
{
    /**
     * @var Uco
     */
    private $provider;

    protected function setUp()
    {
        $this->provider = new Uco([
            'clientId' => 'mock_client_id',
            'clientSecret' => 'mock_secret',
            'redirectUri' => 'mock_redirect_uri',
        ]);
    }

    public function testAuthorizationUrl()
    {
        $url = $this->provider->getAuthorizationUrl();
        $uri = parse_url($url);
        parse_str($uri['query'], $query);
        $this->assertArrayHasKey('client_id', $query);
        $this->assertArrayHasKey('response_type', $query);
        $this->assertArrayHasKey('redirect_uri', $query);
    }
}
