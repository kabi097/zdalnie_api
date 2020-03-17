<?php

namespace App\Tests\Functional;

use App\Entity\Category;
use App\Entity\Post;
use App\Entity\User;
use App\Test\CustomApiTestCase;
use Hautelook\AliceBundle\PhpUnit\ReloadDatabaseTrait;

class PostResourceTest extends CustomApiTestCase {

    use ReloadDatabaseTrait;

    public function testCreatePost() {
        self::bootKernel();

        $client = self::createClient();
        $this->createUserAndLogIn($client, 'jan@testowy.pl', 'qwerty123');

    }

    public function testUpdatePost() {
//        self::bootKernel();
        $client = self::createClient();
        $user1 = $this->createUser('jan@testowy.pl', 'qwerty123');
        $user2 = $this->createUser('janusz@testowy.pl', 'qwerty123');

        $category = new Category();
        $category->setName('Ogólne');

        $post = new Post('aaaaaaaaaa', 'bbbbbbbbbbbb', 2000, 7);
        $post->setUser($user1);
        $post->setCategory($category);

        $em = $this->getEntityManager();
        $em->persist($category);
        $em->persist($post);
        $em->flush();

        $jwtUser1 = $this->getJWTToken($client,'jan@testowy.pl', 'qwerty123');
        $jwtUser2 = $this->getJWTToken($client,'janusz@testowy.pl', 'qwerty123');

        $client->request('PUT', '/api/posts/'.$post->getId(), [
            'headers' => [
                'Authorization' => 'Bearer ' . $jwtUser1,
            ],
            'json' => [
                'title' => 'updated'
            ]
        ]);
        $this->assertResponseStatusCodeSame(200);
        $this->assertContains('bbbbbbbbbbbb', $client->getResponse()->toArray());

        $client->request('PUT', '/api/posts/'.$post->getId(), [
            'headers' => [
                'Authorization' => 'Bearer ' . $jwtUser2,
            ],
            'json' => [
                'title' => 'updated2'
            ]
        ]);
        $this->assertResponseStatusCodeSame(403);
    }
}