<?php

namespace App\Tests;

use ApiPlatform\Core\Bridge\Symfony\Bundle\Test\ApiTestCase;
use ApiPlatform\Core\Bridge\Symfony\Bundle\Test\Client;
use Hautelook\AliceBundle\PhpUnit\RefreshDatabaseTrait;

abstract class AbstractTest extends ApiTestCase
{
    private $token;
    private $clientWithCredentials;

    use RefreshDatabaseTrait;

    public function setUp(): void
    {
        self::bootKernel();
    }

    protected function createClientWithCredentials($token = null): Client
    {
        $token = $token ?: $this->getToken();

        $options =  ['headers' => ['Content-Type' => 'application/ld+json', 'authorization' => 'Bearer ' . $token]];

        return static::createClient([], $options);
        // return static::createClient([], ['headers' => ['authorization' => 'Bearer ' . $token]]);
    }

    /**
     * Use other credentials if needed.
     */
    protected function getToken($body = []): string
    {
        if ($this->token) {
            return $this->token;
        }

        $client = static::createClient();
        $response = $client->request('POST', '/authentication_token', [
            'json' => $body ?: [
                'email' => 'testadmin@gmail.com',
                'password' => 'testadmin',
            ],
            'headers' => ['Content-Type' => 'application/json', 'accept' => 'application/json'],
        ]);


        $this->assertResponseIsSuccessful();
        $data = json_decode($response->getContent());
        $this->token = $data->token;

        return $data->token;
    }
}
