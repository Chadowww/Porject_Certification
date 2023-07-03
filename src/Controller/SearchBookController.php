<?php

namespace App\Controller;

use App\Form\FilterType;
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
        $categories = [];
        $form = $this->createForm(FilterType::class);
        $form->handleRequest($request);

        // Récupère les données du formulaire depuis la requête
        $searchTerm [] = $request->query->get('search_book');

        // Utilise les données pour effectuer ta recherche
        $books = $bookRepository->searchBook($searchTerm);
        foreach ($books as $book) {
            $categories[] = $book->getCategory();
        }
        // Effectue d'autres traitements ou renvoie une réponse appropriée
        return $this->render('search/search.html.twig', [
            'books' => $books,
            'form' => $form->createView(),
            'searchTerm' => $searchTerm,
            'categories' => $categories
        ]);
    }
}
