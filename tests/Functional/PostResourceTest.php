<?php

namespace App\Tests\Functional;

use ApiPlatform\Core\Bridge\Symfony\Bundle\Test\ApiTestCase;
use App\Entity\User;

class PostResourceTest extends ApiTestCase {

    use ReloadDatabaseTrait;

    public function testCreatePostListing() {
        $client = self::createClient();
        $client->request('POST', '/api/posts', [
            'headers' => ['Content-Type' => 'application/json'],
            'json' => [],
        ]);
        $this->assertResponseStatusCodeSame(401);

        $user = new User();
        $user->setEmail('test@testowy.pl');
        $user->setUsername('tester');
        $user->setPassword('$argon2i$v=19$m=65536,t=4,p=1$ZXIzS2xqT3g5T21KNHkwQg$j1MId4KzWxVJeN8MYUaG59GQD8JvlDBn423T6TGXMjQ');
        $user->setType(true);

        $em = self::$container->get('doctrine')->getManager();
        $em->persist($user);
        $em->flush();

        $client->request('POST', '/login', [
            'headers' => ['Content-Type' => 'application/json'],
            'json' => [
                'email' => 'test@testowy.pl',
                'password' => 'qwerty123',
            ],
        ]);

        $this->assertResponseStatusCodeSame(200);
    }
}