<?php

namespace App\Controller;

use App\Service\ApiClient;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Psr\Log\LoggerInterface;

class HomeController extends AbstractController
{
    private $apiClient;

    private $logger;

    public function __construct(ApiClient $apiClient, LoggerInterface $logger)
    {
        $this->apiClient = $apiClient;
        $this->logger = $logger;
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

                $this->logger->info('Results of movie search request for `' . urlencode($query) . '` at page ' . $page . ':', [
                    'count' => count($movies['results']),
                    'page' => $movies['page'],
                    'total_pages' => $movies['total_pages'],
                ]);
            } catch (\Exception $e) {
                $this->addFlash('error', 'Une erreur est survenue lors de la recherche des films.');

                $this->logger->error('Error when trying to search movies for `' . urlencode($query) . '` at page ' . $page . ':', [
                    'exception' => $e->getMessage(),
                    'query' => $query,
                    'page' => $page,
                ]);
            }
        }

        return $this->render('home/index.html.twig', [
            'movies' => $movies,
        ]);
    }
}
