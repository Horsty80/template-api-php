<?php

namespace App\Tests;

final class UsersTest extends AbstractTest
{
    public function testAdminResource()
    {
        $response = $this->createClientWithCredentials()->request('GET', '/books');
        $this->assertResponseIsSuccessful();
    }

    // fail on asset in getToken function
    // If wrong credentials -> return {"code":401,"message":"Invalid credentials."}
    // Need to rewrite method to handle this

    // public function testLoginAsUnknownUser()
    // {
    //     $token = $this->getToken([
    //         'email' => 'unknown-user@example.com',
    //         'password' => 'no-password',
    //     ]);

    //     $response = $this->createClientWithCredentials($token)->request('GET', '/books');
    //     $this->assertJsonContains(['hydra:description' => 'Access Denied.']);
    //     $this->assertResponseStatusCodeSame(403);
    // }
}
