<?php

namespace App\Tests\Functional;

use App\Test\CustomApiTestCase;
use Hautelook\AliceBundle\PhpUnit\ReloadDatabaseTrait;

class UserResourceTest extends CustomApiTestCase
{
    use ReloadDatabaseTrait;

    public function testUpdateUser()
    {
        $client = self::createClient();
        $user = $this->createUser('test2@testowy.pl', 'qwerty123');
        $jwtUser1 = $this->getJWTToken($client, 'test2@testowy.pl', 'qwerty123');

        $client->request('PUT', '/api/users/' . $user->getId(), [
            'headers' => [
                'Authorization' => 'Bearer ' . $jwtUser1,
            ],
            'json' => [
                'username' => 'janusz'
            ]
        ]);
        $this->assertResponseIsSuccessful();
        $this->assertContains('janusz', $client->getResponse()->toArray());

        $client->request('PUT', '/api/users/' . $user->getId(), [
            'headers' => [
                'Authorization' => 'Bearer ' . $jwtUser1,
            ],
            'json' => [
                'password' => ''
            ]
        ]);
        $this->assertResponseStatusCodeSame('400');
    }

    public function testGetUser() {
        $client = self::createClient();
        $user = $this->createUser('getusertest@testowy.pl', 'qwerty123');
        $jwtUser1 = $this->getJWTToken($client, 'getusertest@testowy.pl', 'qwerty123');

        $client->request('GET', '/api/posts', [
           'headers' => [
               'Authorization' => 'Bearer ' . $jwtUser1,
           ],
           'json' => []
        ]);
        $response = $client->getResponse()->toArray();
        $this->assertArrayNotHasKey('rules');
    }
}