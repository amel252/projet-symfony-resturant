<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;

#[Route('api/restaurant', name:'app_api_restaurant_')]
class RestaurantController extends AbstractController
{
    #[Route(methods:'POST')]
    public function new(): Response
    {
    }
    #[Route(name:'show' ,methods:'GET')]
    public function show(): Response
    {

    }
    #[Route(name: 'edit', methods:'PUT')]
    public function edit(): Response
    {

    }
    #[Route(name:'delete', methods:'DELETE')]
    public function delete(): Response
    {

    }
    // #[Route('/restaurant', name: 'app_restaurant')]
    // public function index(): Response
    // {
    //     return $this->render('restaurant/index.html.twig', [
    //         'message' => 'Bonjour tout le monde',
    //         'controller_name' => 'RestaurantController',
    //     ]);
    // }
}
