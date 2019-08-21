<?php

namespace App\Service;

use App\Entity\Post;
use App\Entity\User;
use Doctrine\ORM\EntityManagerInterface;

final class UsersService
{
    /** @var EntityManagerInterface */
    private $em;

    /**
     * PostService constructor.
     * @param EntityManagerInterface $em
     */
    public function __construct(EntityManagerInterface $em)
    {
        $this->em = $em;
    }

    /**
     * @param string $message
     * @return Post
     */
    public function createUser(string $message): Post
    {
        $postEntity = new Post();
        $postEntity->setMessage($message);
        $this->em->persist($postEntity);
        $this->em->flush();

        return $postEntity;
    }

    /**
     * @return object[]
     */
    public function getAll(): array
    {
        return $this->em->getRepository(User::class)->findBy([], ['id' => 'DESC']);
    }

    /**
     * @param string $email
     * @return object[]
     */
    public function findeByEmail(string $email): array
    {
        return $this->em->getRepository(User::class)->findBy(['email' => $email], ['id' => 'DESC']);
    }
}
