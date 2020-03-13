<?php
namespace App\Test;

use ApiPlatform\Core\Bridge\Symfony\Bundle\Test\ApiTestCase;
use ApiPlatform\Core\Bridge\Symfony\Bundle\Test\Client;
use App\Entity\User;

class CustomApiTestCase extends ApiTestCase
{

    protected function createUser(string $email, string $password) : User {
       $user = new User();
       $user->setEmail($email);
//       $user->setPassword(self::$container->get('security.password_encoder')->encodePassword($user, $password));
       $user->setPassword($password);
       $user->setUsername(substr($email, 0, strpos($email, '@')));
       $user->setType(true);

       $em = self::$container->get('doctrine')->getManager();
       $em->persist($user);
       $em->flush();

       return $user;
    }

    protected function getEntityManager() {
        return self::$container->get('doctrine')->getManager();
    }

    protected function logIn(Client $client, string $email, string $password) {
        $response = $client->request('POST', '/login', [
            'json' => [
                'email' => $email,
                'password' => $password,
            ],
        ]);
        $this->assertResponseStatusCodeSame(200);
    }

    protected function createAuthenticatedClient(Client $client, string $email, string $password) {
        $response = $client->request('POST', '/login', [
            'json' => [
                'email' => $email,
                'password' => $password,
            ],
        ]);

        dump($response);

        $data = json_decode($client->getResponse()->getContent(), true);

        $client->setServerParameter('HTTP_Authorization', sprintf('Bearer %s', $data['token']));

        return $client;
    }


    protected function createUserAndLogIn(Client $client, string $email, string $password) {
        $user = $this->createUser($email, $password);
        $this->logIn($client, $email, $password);
    }
}