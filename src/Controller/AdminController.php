<?php

namespace App\Controller;

use App\Form\AuthorType;
use App\Form\BookType;
use App\Form\BorrowType;
use App\Form\CategoryType;
use App\Form\CommentType;
use App\Form\EditorType;
use App\Form\ReservationType;
use App\Form\UserType;
use App\Repository\AuthorRepository;
use App\Repository\BookRepository;
use App\Repository\BorrowRepository;
use App\Repository\CategoryRepository;
use App\Repository\CommentRepository;
use App\Repository\EditorRepository;
use App\Repository\ReservationRepository;
use App\Repository\UserRepository;
use Doctrine\ORM\EntityManagerInterface;
use Knp\Component\Pager\PaginatorInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Form\Extension\Core\Type\CheckboxType;
use Symfony\Component\Form\Extension\Core\Type\IntegerType;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Security\Http\Attribute\IsGranted;
use Symfony\Component\HttpFoundation\Response;

#[Route('/admin')]
class AdminController extends AbstractController
{

    #[Route('/', name: 'admin')]
    #[IsGranted('ROLE_ADMIN')]
    public function index(): Response
    {
        return $this->render('admin/index.html.twig');
    }

    #[Route('/author', name: 'app_admin_author', methods: ['GET', 'POST'])]
    #[IsGranted('ROLE_ADMIN')]
    public function Author(AuthorRepository $authorRepository, Request $request, EntityManagerInterface $manager, PaginatorInterface $paginator):
    Response
    {
        $authors = $authorRepository->findAll();

		$forms = [];
		foreach ($authors as $author) {
            $form = $this->createForm(AuthorType::class,$author,[
                'action' => $this->generateUrl('app_admin_author'),
                'method' => 'POST',
            ])
                ->add('id', IntegerType::class, [
                    'label' => 'ID',
                    'data' => $author->getId(),
                    'mapped' => false, // Important : désactiver le mappage pour éviter l'erreur
                ]);

            $forms[] = $form->createView();
        }

        $forms = $paginator->paginate(
            $forms,
            $request->query->getInt('page', 1),
            20
        );

        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $author = $authorRepository->findOneBy(['id' => $request->request->all()['author']['id']]);
            $author->setName($request->request->all()['author']['name']);
            $author->setBiography($request->request->all()['author']['biography']);
            $author->setAvatar($request->request->all()['author']['avatar']);
            $manager->persist($author);
            $manager->flush();
			return $this->redirectToRoute('app_admin_author');
        }


        return $this->render('admin/view/author.html.twig', [
            'authors' => $authors,
            'forms' => $forms,
        ]);
    }

    /**
     * @throws \Exception
     */
    #[Route('/book', name: 'app_admin_book')]
    #[IsGranted('ROLE_ADMIN')]
    public function book(BookRepository $bookRepository, Request $request, EntityManagerInterface $manager, PaginatorInterface $paginator):
    Response
    {
        $books = $bookRepository->findAll();
		$forms = [];

        foreach ($books as $book) {
            $form = $this->createForm(BookType::class,$book,[
                'action' => $this->generateUrl('app_admin_book'),
                'method' => 'POST',
            ])
                ->add('id', IntegerType::class, [
                    'label' => 'ID',
                    'data' => $book->getId(),
                    'mapped' => false, // Important : désactiver le mappage pour éviter l'erreur
                ]);
            $forms[] = $form->createView();

        }
        $forms = $paginator->paginate(
            $forms,
            $request->query->getInt('page', 1),
            20
        );
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()){
            $book = $bookRepository->findOneBy(['id' => $request->request->all()['book']['id']]);
            $book->setTitle($request->request->all()['book']['title']);
            $book->setDescription($request->request->all()['book']['description']);
            $book->setPublish(new \DateTime($request->request->all()['book']['publish']));
            $book->setQteStock($request->request->all()['book']['qteStock']);
            $book->setQteCheckout($request->request->all()['book']['qteCheckout']);
            $manager->persist($book);
            try {
                $manager->flush();
	                $this->addFlash('success', 'Le livre a bien été modifié');
            }catch (\Exception $e) {
                $this->addFlash('error', 'Une erreur est survenue lors de la modification du livre');
            }
			return $this->redirectToRoute('app_admin_book');
        }

        return $this->render('admin/view/book.html.twig', [
            'books' => $books,
            'forms' => $forms,
        ]);
    }

    #[Route('/borrow', name: 'app_admin_borrow')]
    #[IsGranted('ROLE_ADMIN')]
    public function borrow(BorrowRepository $borrowRepository, Request $request, EntityManagerInterface $manager, PaginatorInterface $paginator):
    Response
    {
        $borrows = $borrowRepository->findAll();
		$forms = [];

        foreach ($borrows as $borrow) {
            $form = $this->createForm(BorrowType::class,$borrow,[
                'action' => $this->generateUrl('app_admin_borrow'),
                'method' => 'POST',
            ])
                ->add('id', IntegerType::class, [
                    'label' => 'ID',
                    'data' => $borrow->getId(),
                    'mapped' => false, // Important : désactiver le mappage pour éviter l'erreur
                ]);
            $forms[] = $form->createView();

        }
        $forms = $paginator->paginate(
            $forms,
            $request->query->getInt('page', 1),
            20
        );

        $form->handleRequest($request);

		if ($form->isSubmitted() && $form->isValid()){
            $borrow = $borrowRepository->findOneBy(['id' => $request->request->all()['borrow']['id']]);
            $borrow->setIsReturned($request->request->all()['borrow']['isReturned']);
            $manager->persist($borrow);
            $manager->flush();
        }
        return $this->render('admin/view/borrow.html.twig', [
            'borrows' => $borrows,
            'forms' => $forms,
        ]);
    }

    #[Route('/category', name: 'app_admin_category')]
    #[IsGranted('ROLE_ADMIN')]
    public function category(CategoryRepository $categoryRepository, Request $request, EntityManagerInterface
    $manager, PaginatorInterface $paginator):
    Response
    {
        $categories = $categoryRepository->findAll();
		$forms = [];

        foreach ($categories as $category) {
            $form = $this->createForm(CategoryType::class,$category,[
                'action' => $this->generateUrl('app_admin_category'),
                'method' => 'POST',
            ])
                ->add('id', IntegerType::class, [
                    'label' => 'ID',
                    'data' => $category->getId(),
                    'mapped' => false, // Important : désactiver le mappage pour éviter l'erreur
                ]);
            $forms[] = $form->createView();

        }
        $form->handleRequest($request);

        $forms = $paginator->paginate(
            $forms,
            $request->query->getInt('page', 1),
            20
        );

        if ($form->isSubmitted() && $form->isValid()){
            $category = $categoryRepository->findOneBy(['id' => $request->request->all()['category']['id']]);
            $category->setName($request->request->all()['category']['name']);
            $manager->persist($category);
            $manager->flush();
        }

        return $this->render('admin/view/category.html.twig', [
            'categories' => $categories,
            'forms' => $forms,
        ]);
    }

    #[Route('/comment', name: 'app_admin_comment')]
    #[IsGranted('ROLE_ADMIN')]
    public function comment(CommentRepository $commentRepository, Request $request, EntityManagerInterface $manager, PaginatorInterface $paginator)
    : Response
    {
        $comments = $commentRepository->findAll();
		$forms = [];

        foreach ($comments as $comment) {
            $form = $this->createForm(CommentType::class,$comment,[
                'action' => $this->generateUrl('app_admin_comment'),
                'method' => 'POST',
            ])
                ->add('id', IntegerType::class, [
                    'label' => 'ID',
                    'data' => $comment->getId(),
                    'mapped' => false, // Important : désactiver le mappage pour éviter l'erreur
                ]);
            $forms[] = $form->createView();

        }

        $form->handleRequest($request);

        $forms = $paginator->paginate(
            $forms,
            $request->query->getInt('page', 1),
            20
        );
        if ($form->isSubmitted() && $form->isValid()){
			$comment = $commentRepository->findOneBy(['id' => $request->request->all()['comment']['id']]);
            $comment->setContent($request->request->all()['comment']['content']);
            $manager->persist($comment);
            $manager->flush();
        }

        return $this->render('admin/view/comment.html.twig', [
            'comments' => $comments,
            'forms' => $forms,
        ]);
    }

    #[Route('/editor', name: 'app_admin_editor')]
    #[IsGranted('ROLE_ADMIN')]
    public function editor(EditorRepository $editorRepository, Request $request, EntityManagerInterface $manager, PaginatorInterface $paginator):
    Response
    {
        $editors = $editorRepository->findAll();
		$forms = [];

        foreach ($editors as $editor) {
            $form = $this->createForm(EditorType::class,$editor,[
                'action' => $this->generateUrl('app_admin_editor'),
                'method' => 'POST',
            ])
                ->add('id', IntegerType::class, [
                    'label' => 'ID',
                    'data' => $editor->getId(),
                    'mapped' => false, // Important : désactiver le mappage pour éviter l'erreur
                ]);
            $forms[] = $form->createView();

        }

        $form->handleRequest($request);

        $forms = $paginator->paginate(
            $forms,
            $request->query->getInt('page', 1),
            20
        );

		if ($form->isSubmitted() && $form->isValid()){
            $editor = $editorRepository->findOneBy(['id' => $request->request->all()['editor']['id']]);
            $editor->setName($request->request->all()['editor']['name']);
            $manager->persist($editor);
            $manager->flush();
            $this->addFlash('success', 'L\'éditeur a bien été modifié');
            $this->redirectToRoute('app_admin_editor');
        }
        return $this->render('admin/view/editor.html.twig', [
            'editors' => $editors,
            'forms' => $forms,
        ]);
    }

    #[Route('/reservation', name: 'app_admin_reservation')]
    #[IsGranted('ROLE_ADMIN')]
    public function reservation(
        ReservationRepository $reservationRepository,
        Request $request,
        EntityManagerInterface $manager,
        PaginatorInterface $paginator
    ): Response
    {
        $reservations = $reservationRepository->findAll();
		$forms = [];

        foreach ($reservations as $reservation) {
            $form = $this->createForm(ReservationType::class,$reservation,[
                'action' => $this->generateUrl('app_admin_reservation'),
                'method' => 'POST',
            ])
                ->add('id', IntegerType::class, [
                    'label' => 'ID',
                    'data' => $reservation->getId(),
                    'mapped' => false, // Important : désactiver le mappage pour éviter l'erreur
                ]);
            $forms[] = $form->createView();

        }

        $form->handleRequest($request);

        $forms = $paginator->paginate(
            $forms,
            $request->query->getInt('page', 1),
            20
        );

        if ($form->isSubmitted() && $form->isValid()){
            $reservation = $reservationRepository->findOneBy(['id' => $request->request->all()['reservation']['id']]);
            $reservation->setDatecheckin($request->request->all()['reservation']['datecheckin']);
            $reservation->setDatecheckout($request->request->all()['reservation']['datecheckout']);
            $reservation->setDescription($request->request->all()['reservation']['description']);
            $reservation->setAllDay($request->request->all()['reservation']['all_day']);
            $reservation->setBackgroundColor($request->request->all()['reservation']['background_color']);
            $reservation->setBorderColor($request->request->all()['reservation']['border_color']);
            $reservation->setTextColor($request->request->all()['reservation']['text_color']);
            $reservation->setStatus($request->request->all()['reservation']['status']);
            $manager->persist($reservation);
            $manager->flush();
        }

        return $this->render('admin/view/reservation.html.twig', [
            'reservations' => $reservations,
            'forms' => $forms,
        ]);
    }

    #[Route('/user', name: 'app_admin_user')]
    #[IsGranted('ROLE_ADMIN')]
    public function user(UserRepository $userRepository, Request $request, EntityManagerInterface $manager, PaginatorInterface $paginator): Response
    {
        $users = $userRepository->findAll();
		$forms = [];
        foreach ($users as $user) {
            $form = $this->createForm(UserType::class,$user,[
                'action' => $this->generateUrl('app_admin_user'),
                'method' => 'POST',
            ])
                ->add('id', IntegerType::class, [
                    'label' => 'ID',
                    'data' => $user->getId(),
                    'mapped' => false, // Important : désactiver le mappage pour éviter l'erreur
                ])
            ->add('isVerified', CheckboxType::class, [
                'label' => 'isVerified',
                'data' => $user->isVerified(),
                'mapped' => false, // Important : désactiver le mappage pour éviter l'erreur
            ]);

            $forms[] = $form->createView();
        }

        $forms = $paginator->paginate(
            $forms,
            $request->query->getInt('page', 1),
            20
        );

        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()){
            $user = $userRepository->findOneBy(['id' => $request->request->all()['user']['id']]);
            $user->setEmail($request->request->all()['user']['email']);
            $user->setRoles($request->request->all()['user']['roles']);
            $user->setPassword($request->request->all()['user']['password']);
            $user->setFirstname($request->request->all()['user']['firstname']);
            $user->setLastname($request->request->all()['user']['lastname']);
            $user->setAddress($request->request->all()['user']['address']);
            $user->setCity($request->request->all()['user']['city']);
            $user->setZcode($request->request->all()['user']['zipcode']);
            $user->setPhone($request->request->all()['user']['phone']);
            $user->setIsVerified($request->request->all()['user']['isVerified']);
        }

        return $this->render('admin/view/user.html.twig', [
            'users' => $users,
            'forms' => $forms,
        ]);
    }

}