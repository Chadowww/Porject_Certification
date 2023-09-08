<?php

namespace App\Controller;

use App\Repository\BookRepository;
use Knp\Component\Pager\PaginatorInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\{Request, Response};
use Symfony\Component\Routing\Annotation\Route;

class SearchBookController extends AbstractController
{

    #[Route('/search', name: 'app_book_search', methods: ['GET'])]
    public function search(Request $request, BookRepository $bookRepository, PaginatorInterface $paginator): Response
    {
        $searchTerm [] = $request->query->get('search_book');

        $books = $bookRepository->searchBook($searchTerm);
		$books = $paginator->paginate(
            $books,
            $request->query->getInt('page', 1),
            12
        );

        return $this->render('search/search.html.twig', [
            'books' => $books,
        ]);
    }
}
