<?php

namespace App\Twig\Components;

use App\Form\SearchBookType;
use App\Repository\AuthorRepository;
use App\Repository\BookRepository;
use App\Repository\CategoryRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Form\FormFactoryInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\UX\LiveComponent\Attribute\AsLiveComponent;
use Symfony\UX\LiveComponent\DefaultActionTrait;

#[AsLiveComponent('navbarComponent')]
final class NavbarComponent extends AbstractController
{
    use DefaultActionTrait;
    public function __construct(
        private CategoryRepository $categoryRepository,
        private AuthorRepository $authorRepository,
        private BookRepository $bookRepository)
    {
    }

    public function getCategories(): array
    {
        return $this->categoryRepository->findAll();
    }
    public function getAuthors(): array
    {
        return $this->authorRepository->findAll();
    }
    public function getBooks(): array
    {
        return $this->bookRepository->findAll();
    }

    public function search(Request $request, FormFactoryInterface $formFactory): Response {
        $form = $formFactory->create(SearchBookType::class);
        $formView = $form->createView();
        return $this->render('_includes/search_form.html.twig', [
            'form' => $formView,
        ]);


    }
}
