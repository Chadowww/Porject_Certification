<?php

namespace App\Twig\Components;

use App\Form\RegistrationFormType;
use App\Form\SearchBookType;
use App\Repository\AuthorRepository;
use App\Repository\BookRepository;
use App\Repository\CategoryRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Form\FormFactoryInterface;
use Symfony\Component\Form\FormInterface;
use Symfony\Component\Form\FormView;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Security\Http\Authentication\AuthenticationUtils;
use Symfony\UX\LiveComponent\Attribute\AsLiveComponent;
use Symfony\UX\LiveComponent\ComponentWithFormTrait;
use Symfony\UX\LiveComponent\DefaultActionTrait;

#[AsLiveComponent('navbarComponent')]
final class NavbarComponent extends AbstractController
{
    use DefaultActionTrait;
    use ComponentWithFormTrait;

    public $error;
    public $registrationForm;
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

//    public function getFormSearch(): FormView
//    {
//        $this->registrationForm = $this->createForm(RegistrationFormType::class);
//
//        return $this->registrationForm->createView();
//    }

    protected function instantiateForm(): FormInterface
    {
        return $this->createForm(RegistrationFormType::class);
    }

    public function getErrors(AuthenticationUtils $authenticationUtils): \Symfony\Component\Security\Core\Exception\AuthenticationException
    {
        $this->error = $authenticationUtils->getLastAuthenticationError();
        return $this->error;
    }

}
