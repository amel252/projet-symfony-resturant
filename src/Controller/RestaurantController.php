<?php

namespace App\Controller;

use App\Entity\Restaurant;
use App\Repository\RestaurantRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;

#[Route('api/restaurant', name: 'app_api_restaurant_')]
class RestaurantController extends AbstractController
{
    public function __construct(
        private EntityManagerInterface $manager,
        private RestaurantRepository $repository
    ) {}

    #[Route(name: 'new', methods: ['POST'])]
    public function new(): Response
    {
        $restaurant = new Restaurant();
        $restaurant->setName('Quai Antique');
        $restaurant->setDescription('Cette qualité et ce goût par le chef Arnauld MICHANT');
        $restaurant->setCreatedAt(new \DateTimeImmutable());

        $this->manager->persist($restaurant);
        $this->manager->flush();

        return $this->json(
            ['message' => "Restaurant resource created with ID {$restaurant->getId()}"],
            Response::HTTP_CREATED
        );
    }

    #[Route('/{id}', name: 'show', methods: ['GET'], requirements: ['id' => '\d+'])]
    public function show(int $id): Response
    {
        $restaurant = $this->repository->find($id);

        if (!$restaurant) {
            throw $this->createNotFoundException("No Restaurant found for ID {$id}");
        }

        return $this->json(
            ['message' => "A Restaurant was found: {$restaurant->getName()} for ID {$restaurant->getId()}"]
        );
    }

    #[Route('/{id}', name: 'edit', methods: ['PUT'])]
    public function edit(int $id): Response
    {
        $restaurant = $this->repository->find($id);

        if (!$restaurant) {
            throw $this->createNotFoundException("No Restaurant found for ID {$id}");
        }

        $restaurant->setName('Restaurant name updated');
        $this->manager->flush();

        return $this->json(
            ['message' => "Restaurant updated", 'id' => $restaurant->getId()]
        );
    }

    #[Route('/{id}', name: 'delete', methods: ['DELETE'])]
    public function delete(int $id): Response
    {
        $restaurant = $this->repository->find($id);

        if (!$restaurant) {
            throw $this->createNotFoundException("No Restaurant found for ID {$id}");
        }

        $this->manager->remove($restaurant);
        $this->manager->flush();

        return $this->json(
            ['message' => "Restaurant resource deleted"],
            Response::HTTP_NO_CONTENT
        );
    }
}
