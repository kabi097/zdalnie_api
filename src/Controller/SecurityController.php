<?php


namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Routing\Annotation\Route;

class SecurityController extends AbstractController {

    /**
     * @Route("/login", name="app_login", methods={"POST"})
     */
    public function login() {

        if (!$this->isGranted('IS_AUTHENTICATED_FULLY')) {
            return $this.$this->json([
                'error' => 'Invalid login request',
            ], 400);
        }

        return $this->json([
            'user' => $this->getUser() ? $this->getUser()->getUsername() : null
        ]);
    }

    /**
     * @Route("/logout", name="app_logout")
     */
    public function logout() {
        throw new \Exception('Shoud not be reached');
    }
}