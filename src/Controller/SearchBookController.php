<?php

namespace App\Controller;

use App\Form\SearchBookType;
use App\Repository\BookRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Form\FormFactoryInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class SearchBookController extends AbstractController
{

    #[Route('/search', name: 'app_book_search', methods: ['GET'])]
    public function search(Request $request, BookRepository $bookRepository): Response
    {
        // Récupère les données du formulaire depuis la requête
        $searchTerm [] = $request->query->get('search_book');
//        $searchTerm [] = $request->query->get('category');
//        $searchTerm []= $request->query->get('author');

        // Utilise les données pour effectuer ta recherche
        $books = $bookRepository->searchBook($searchTerm);

        // Effectue d'autres traitements ou renvoie une réponse appropriée
        return $this->render('search/search.html.twig', [
            'books' => $books,
        ]);
    }
}
