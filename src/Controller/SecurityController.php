<?php

namespace App\Controller;

use App\Entity\User;
use DateTimeImmutable;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Messenger\Transport\Serialization\Serializer;
use Symfony\Component\Messenger\Transport\Serialization\SerializerInterface;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;
use Symfony\Component\Routing\Attribute\Route;
use Symfony\Component\Security\Http\Attribute\CurrentUser;

#[Route('/api', name:'app_api_')]

class SecurityController extends AbstractController
{
    public function __construct(
        private SerializerInterface $serializer,
        private EntityManagerInterface $entityManager
    ) {}
//******************* route inscription */
    #[Route('/registration', name: 'registration', methods: ['POST'])]
    public function register(Request $request, UserPasswordHasherInterface $passwordHasher): JsonResponse
    {
        try {
            $user = $this->serializer->deserialize($request->getContent(), User::class, 'json');

            if (!$user instanceof User) {
                return new JsonResponse(['error' => 'Invalid user data'], Response::HTTP_BAD_REQUEST);
            }

            $hashedPassword = $passwordHasher->hashPassword($user, $user->getPassword());
            $user->setPassword($hashedPassword);
            $user->setCreatedAt(new DateTimeImmutable());

            $this->entityManager->persist($user);
            $this->entityManager->flush();

            return new JsonResponse(
                [
                    'user' => $user->getUserIdentifier(),
                    'apiToken' => $user->getApiToken(),
                    'roles' => $user->getRoles()
                ],
                Response::HTTP_CREATED
            );
        } catch (\Exception $e) {
            return new JsonResponse(['error' => 'Invalid request: ' . $e->getMessage()], Response::HTTP_BAD_REQUEST);
        }
    }
    //************************** route connexion */
    #[Route('/login', name: 'login', methods: ['POST'])]
    public function login(#[CurrentUser] ?User $user): JsonResponse
    {
        if (null === $user) {
            return new JsonResponse(['message' => 'Missing credentials'], Response::HTTP_UNAUTHORIZED);
        }
        return new JsonResponse([
            'user'  => $user->getUserIdentifier(),
            'roles' => $user->getRoles(),
            'apiToken' => method_exists($user, 'getApiToken') ? $user->getApiToken() : null,
    ], Response::HTTP_OK);
    }
}