<?php

namespace App\Tests\Functional;

use App\Entity\Category;
use App\Entity\Post;
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
        self::bootKernel();
        $user = $this->createUser('jan@testowy.pl', 'qwerty123');

        $category = new Category();
        $category->setName('Ogólne');

        $post = new Post('Zlecę stworzenie strony internetowej', 'Lorem ipsum dolor sit amet...', 2000, 7);
        $post->setUser($user);
        $post->setCategory($category);

        $em = $this->getEntityManager();
        $em->persist($category);
        $em->persist($post);
        $em->flush();

        $client = self::createClient();
        $client = $this->createAuthenticatedClient($client, 'jan@testowy.pl', 'qwerty123');
        $client->request('PUT', '/api/posts', 'foo');
        $this->assertResponseStatusCodeSame(200);
    }
}