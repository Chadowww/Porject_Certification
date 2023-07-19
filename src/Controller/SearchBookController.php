<?php

namespace App\Controller;

use App\Form\SearchBookType;
use App\Repository\BookRepository;
use Knp\Component\Pager\PaginatorInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Form\FormFactoryInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
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
//        $books->setTemplate('bundles/knp_pagination/sliding.html.twig');
//        $books->setSortableTemplate('bundles/knp_pagination/sliding_sortable_link.html.twig');

        return $this->render('search/search.html.twig', [
            'books' => $books,
        ]);
    }
}
