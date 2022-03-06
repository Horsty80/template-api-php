<?php

namespace App\Tests;

final class UsersTest extends AbstractTest
{
    public function testAdminResource()
    {
        $response = $this->createClientWithCredentials()->request('GET', '/users');
        $this->assertResponseIsSuccessful();
    }
}
