<?php

namespace App\Doctrine;

use App\Entity\Post;
use Symfony\Component\Security\Core\Security;

class SetOwnerListener
{
    /**
     * @var Security
     */
    private $security;

    public function __construct(Security $security)
    {
        $this->security = $security;
    }

    public function prePersist(Post $post) {
        if ($post->getUser()) return;
        if ($this->security->getUser()) {
            $post->setUser($this->security->getUser());
        }
    }
}