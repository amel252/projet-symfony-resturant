<?php

namespace App\Controller;

use App\Entity\Restaurant;
use DateTimeImmutable ;

use App\Repository\RestaurantRepository;
use Doctrine\ORM\EntityManagerInterface;
use PHPUnit\Util\Json;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\{JsonResponse, Request, Response};
use Symfony\Component\Messenger\Transport\Serialization\Serializer;
use Symfony\Component\Routing\Generator\UrlGeneratorInterface;
use Symfony\Component\Serializer\SerializerInterface;
use Symfony\Component\Routing\Attribute\Route;


#[Route('api/restaurant', name: 'app_api_restaurant_')]
class RestaurantController extends AbstractController
{
    public function __construct(
        private EntityManagerInterface $manager,
        private RestaurantRepository $repository,
        private SerializerInterface $serializer,
        private UrlGeneratorInterface $urlGenerator
    ) {}
//************************************** */
#[Route(methods: ['POST'])]
public function new(Request $request): JsonResponse
{
    // Désérialisation des données envoyées dans la requête JSON vers l'objet Restaurant
    $restaurant = $this->serializer->deserialize($request->getContent(), Restaurant::class, 'json');

    // Définir la date de création
    $restaurant->setCreatedAt(new \DateTimeImmutable());

    // Persist l'entité
    $this->manager->persist($restaurant);
    $this->manager->flush();

    // Retourner une réponse JSON avec un message de succès
    return new JsonResponse(['message' => 'Restaurant créé'], Response::HTTP_CREATED);
}

// ************************************
    #[Route('/{id}', name: 'show', methods: ['GET'], requirements: ['id' => '\d+'])]
    public function show(int $id): JsonResponse
    {
    $restaurant = $this->repository->find($id);

    if ($restaurant) {
        // Sérialiser les données du restaurant en JSON
        $responseData = $this->serializer->serialize($restaurant, 'json');

        // Retourner les données JSON avec un code de statut 200
        return new JsonResponse($responseData, Response::HTTP_OK, [], true);
    }

    // Si le restaurant n'est pas trouvé, retourner un 404
    return new JsonResponse(null, Response::HTTP_NOT_FOUND);

    // Si le restaurant n'est pas trouvé
    return new JsonResponse(null, Response::HTTP_NOT_FOUND);
    }

//************************ */
    #[Route('/{id}', name: 'edit', methods: ['PUT'])]
    public function edit(int $id): JsonResponse
    {
    $restaurant = $this->repository->find($id);
    if ($restaurant) {
        // Vous devez probablement modifier des données ici avant de "flush"
        $this->manager->flush();
        return new JsonResponse(null, Response::HTTP_NO_CONTENT); // Réponse HTTP 204 sans contenu
    }

    return new JsonResponse(null, Response::HTTP_NOT_FOUND); // Réponse HTTP 404 si restaurant non trouvé
    }

//*************************** */
    #[Route('/{id}', name: 'delete', methods: ['DELETE'])]
    public function delete(int $id): JsonResponse
    {
        $restaurant = $this->repository->find($id);

        if ($restaurant) {
            $this->manager->flush();

            return new JsonResponse(data:null, status:Response::HTTP_NO_CONTENT);
        }
        return new JsonResponse(data:null, status:Response::HTTP_NOT_FOUND);
    }
}
