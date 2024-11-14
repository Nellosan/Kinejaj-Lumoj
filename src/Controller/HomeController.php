<?php

namespace App\Controller;

use App\Service\ApiClient;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class HomeController extends AbstractController
{
    private $apiClient;

    public function __construct(ApiClient $apiClient)
    {
        $this->apiClient = $apiClient;
    }

    #[Route('/', name: 'home')]
    public function index(Request $request): Response
    {
        $query = $request->query->get('query', '');
        $page = $request->query->getInt('page', 1);
        $movies = [];

        if (!empty($query)) {
            try {
                $movies = $this->apiClient->getData('search/movie', ['query' => urlencode($query), 'page' => $page, 'language' => 'fr-FR']);
            } catch (\Exception $e) {
                $this->addFlash('error', 'Une erreur est survenue lors de la recherche des films.');
            }
        }

        return $this->render('home/index.html.twig', [
            'movies' => $movies,
        ]);
    }
}
