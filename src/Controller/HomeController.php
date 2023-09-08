<?php

namespace App\Controller;

use App\Repository\{AuthorRepository, BookRepository, CategoryRepository, EditorRepository};
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class HomeController extends AbstractController
{
    #[Route('/', name: 'app_home')]
    public function index(
        CategoryRepository $categoryRepository,
        AuthorRepository $authorRepository,
        BookRepository $bookRepository,
        EditorRepository $editorRepository
    ): Response
    {

        return $this->render('home/index.html.twig', [
            'categories' => $categoryRepository->findAll(),
            'authors' => $authorRepository->findAll(),
            'editors' => $editorRepository->findAll(),
            'mostFavoriteBooks' => $bookRepository->mostFavoriteBooks(),
            'lastBooks' => $bookRepository->lastBooks(),
            'randBooks' => $bookRepository->randBooks(),
        ]);
    }
}
