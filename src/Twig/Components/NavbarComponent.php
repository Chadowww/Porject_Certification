<?php

namespace App\Twig\Components;

use App\Repository\AuthorRepository;
use App\Repository\BookRepository;
use App\Repository\CategoryRepository;
use Symfony\UX\LiveComponent\Attribute\AsLiveComponent;
use Symfony\UX\LiveComponent\DefaultActionTrait;

#[AsLiveComponent('navbarComponent')]
final class NavbarComponent
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
}
