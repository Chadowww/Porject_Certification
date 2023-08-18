<?php

namespace App\Controller;

use App\Form\{AdminReservationType, AdminUserType, AuthorType, BookType, BorrowType, CategoryType};
use App\Repository\{AuthorRepository, BookRepository, BorrowRepository, CategoryRepository, CommentRepository, EditorRepository, ReservationRepository, UserRepository};
use Doctrine\ORM\EntityManagerInterface;
use Knp\Component\Pager\PaginatorInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Security\Http\Attribute\IsGranted;
use Symfony\Component\HttpFoundation\Response;
use Symfony\UX\Chartjs\Builder\ChartBuilderInterface;
use Symfony\UX\Chartjs\Model\Chart;

#[Route('/admin')]
#[IsGranted('ROLE_ADMIN')]
class AdminController extends AbstractController
{

    public function __construct(
        BookRepository $bookRepository,
        AuthorRepository $authorRepository,
        CategoryRepository $categoryRepository,
        EditorRepository $editorRepository,
        ReservationRepository $reservationRepository,
        BorrowRepository $borrowRepository,
        UserRepository $userRepository,
        CommentRepository $commentRepository,
        EntityManagerInterface $manager,
        PaginatorInterface $paginator)
    {

        $this->bookRepository = $bookRepository;
        $this->authorRepository = $authorRepository;
        $this->categoryRepository = $categoryRepository;
        $this->editorRepository = $editorRepository;
        $this->reservationRepository = $reservationRepository;
        $this->borrowRepository = $borrowRepository;
        $this->userRepository = $userRepository;
        $this->commentRepository = $commentRepository;
        $this->manager = $manager;
        $this->paginator = $paginator;
    }

    #[Route('/', name: 'app_admin')]
    public function index(): Response
    {

        return $this->render('admin/index.html.twig',[
            'controller_name' => 'AdminController',
        ]);
    }

    #[Route('/author', name: 'app_admin_author', methods: ['GET', 'POST'])]
    public function Author(Request $request,): Response
    {
        $authors = $this->authorRepository->findAll();

        $authors = $this->paginator->paginate(
            $authors,
            $request->query->getInt('page', 1),
            20
        );

        $form = $this->createForm(AuthorType::class);
        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()) {
            $author = $form->getData();
            $this->manager->persist($author);
            try {
                $this->manager->flush();
                $this->addFlash('success', 'L\'auteur a bien été ajouté');
            } catch (\Exception $e) {
                $this->addFlash('error', 'Une erreur est survenue lors de l\'ajout de l\'auteur');
            }
        }

        if ($request->isMethod('POST') && $request->request->all()['id'] !== null){
            $author = $this->authorRepository->findOneBy(['id' => $request->request->all()['id']]);
            $author->setName($request->request->all()['name']);
            $author->setBiography($request->request->all()['biography']);
            $author->setAvatar($request->request->all()['avatar']);

            $this->manager->persist($author);
            try {
                $this->manager->flush();
                $this->addFlash('success', 'L\'auteur a bien été modifié');
            } catch (\Exception $e) {
                $this->addFlash('error', 'Une erreur est survenue lors de la modification de l\'auteur');
            }
        }

        return $this->render('admin/view/author.html.twig', [
            'authors' => $authors,
            'form' => $form->createView(),
        ]);
    }


    /**
     * @throws \Exception
     */
    #[Route('/book', name: 'app_admin_book')]
    public function book(Request $request): Response
    {
        $books = $this->bookRepository->findAll();

        $books = $this->paginator->paginate(
            $books,
            $request->query->getInt('page', 1),
            20
        );

        $form = $this->createForm(BookType::class);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $book = $form->getData();
            $this->manager->persist($book);
            try {
                $this->manager->flush();
                $this->addFlash('success', 'Le livre a bien été ajouté');
            } catch (\Exception $e) {
                $this->addFlash('error', 'Une erreur est survenue lors de l\'ajout du livre');
            }
        }

        if ($request->isMethod('POST') && isset($request->request->all()['id'])){
			$book = $this->bookRepository->findOneBy(['id' => $request->request->all()['id']]);
            $book->setTitle($request->request->all()['title']);
            $book->setDescription($request->request->all()['description']);
            $book->setPublish(new \DateTime($request->request->all()['publish']));
            $book->setQteStock($request->request->all()['qteStock']);
            $book->setQteCheckout($request->request->all()['qteCheckout']);
            $book->setCover($request->request->all()['cover']);
            $this->manager->persist($book);
            try {
                $this->manager->flush();
                $this->addFlash('success', 'Le livre a bien été modifié');
            } catch (\Exception $e) {
                $this->addFlash('error', 'Une erreur est survenue lors de la modification du livre');
            }
        }

        return $this->render('admin/view/book.html.twig', [
            'books' => $books,
            'form' => $form->createView(),
        ]);
    }


    /**
     * @throws \Exception
     */
    #[Route('/borrow', name: 'app_admin_borrow')]
    public function borrow( Request $request): Response
    {
        $borrows = $this->borrowRepository->findAll();

        $borrows = $this->paginator->paginate(
            $borrows,
            $request->query->getInt('page', 1),
            20
        );

        $form = $this->createForm(BorrowType::class);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $borrow = $form->getData();
            $this->manager->persist($borrow);
            try {
                $this->manager->flush();
                $this->addFlash('success', 'L\'emprunt a bien été créé');
            } catch (\Exception $e) {
                $this->addFlash('error', 'Une erreur est survenue lors de l\'ajout de l\'emprunt');
            }
        }

        if ($request->isMethod('POST') && $request->request->all()['id'] !== null){
            $borrow = $this->borrowRepository->findOneBy(['id' => $request->request->all()['id']]);
            $borrow->setCheckin(new \DateTime($request->request->all()['checkin']));
            $borrow->setCheckout(new \DateTime($request->request->all()['checkout']));

            $this->manager->persist($borrow);
            try {
                $this->manager->flush();
                $this->addFlash('success', 'L\'emprunt a bien été modifié');
            } catch (\Exception $e) {
                $this->addFlash('error', 'Une erreur est survenue lors de la modification de l\'emprunt');
            }
        }

        return $this->render('admin/view/borrow.html.twig', [
            'borrows' => $borrows,
            'form' => $form->createView(),
        ]);
    }

    #[Route('/category', name: 'app_admin_category')]
    public function category(Request $request): Response
    {
        $categories = $this->categoryRepository->findAll();

        $categories = $this->paginator->paginate(
            $categories,
            $request->query->getInt('page', 1),
            20
        );

        $form = $this->createForm(CategoryType::class);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $category = $form->getData();
            $this->manager->persist($category);
            try {
                $this->manager->flush();
                $this->addFlash('success', 'La catégorie a bien été ajoutée');
            } catch (\Exception $e) {
                $this->addFlash('error', 'Une erreur est survenue lors de l\'ajout de la catégorie');
            }
            return $this->redirectToRoute('app_admin_category');
        }

		if ($request->isMethod('POST') && $request->request->all()['id'] !== null){
            $category = $this->categoryRepository->findOneBy(['id' => $request->request->all()['id']]);
            $category->setName($request->request->all()['name']);
            $this->manager->persist($category);
            try {
                $this->manager->flush();
                $this->addFlash('success', 'La catégorie a bien été modifiée');
            } catch (\Exception $e) {
                $this->addFlash('error', 'Une erreur est survenue lors de la modification de la catégorie');
            }
            return $this->redirectToRoute('app_admin_category');
        }

        return $this->render('admin/view/category.html.twig', [
            'categories' => $categories,
            'form' => $form->createView(),
        ]);
    }

    #[Route('/comment', name: 'app_admin_comment')]
    public function comment(Request $request): Response
    {
        $comments = $this->commentRepository->findAll();

        $comments = $this->paginator->paginate(
            $comments,
            $request->query->getInt('page', 1),
            20
        );

		if ($request->isMethod('POST')){
            $comment = $this->commentRepository->findOneBy(['id' => $request->request->all()['id']]);
            $comment->setComment($request->request->all()['comment']);
            $comment->setNote($request->request->all()['note']);
            $this->manager->persist($comment);
            try {
                $this->manager->flush();
                $this->addFlash('success', 'Le commentaire a bien été modifié');
            } catch (\Exception $e) {
                $this->addFlash('error', 'Une erreur est survenue lors de la modification du commentaire');
            }
            return $this->redirectToRoute('app_admin_comment');
        }

        return $this->render('admin/view/comment.html.twig', [
            'comments' => $comments,
        ]);
    }

    #[Route('/editor', name: 'app_admin_editor')]
    public function editor(Request $request): Response
    {
        $editors = $this->editorRepository->findAll();

        $editors = $this->paginator->paginate(
            $editors,
            $request->query->getInt('page', 1),
            20
        );

  		if ($request->isMethod('POST')){
            $editor = $this->editorRepository->findOneBy(['id' => $request->request->all()['id']]);
            $editor->setName($request->request->all()['name']);

            $this->manager->persist($editor);
            try {
                $this->manager->flush();
                $this->addFlash('success', 'L\'éditeur a bien été modifié');
            } catch (\Exception $e) {
                $this->addFlash('error', 'Une erreur est survenue lors de la modification de l\'éditeur');
            }
            return $this->redirectToRoute('app_admin_editor');
        }
        return $this->render('admin/view/editor.html.twig', [
            'editors' => $editors,
        ]);
    }

    #[Route('/reservation', name: 'app_admin_reservation')]
    public function reservation(Request $request): Response
    {
        $reservations = $this->reservationRepository->findAll();

        $reservations = $this->paginator->paginate(
            $reservations,
            $request->query->getInt('page', 1),
            20
        );
        $form = $this->createForm(AdminReservationType::class);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $reservation = $form->getData();
            $this->manager->persist($reservation);
            try {
                $this->manager->flush();
                $this->addFlash('success', 'La réservation a bien été ajoutée');
            } catch (\Exception $e) {
                $this->addFlash('error', 'Une erreur est survenue lors de l\'ajout de la réservation');
            }
            return $this->redirectToRoute('app_admin_reservation');
        }

        if ($request->isMethod('POST') && $request->request->all()['id'] !== null){
            $reservation = $this->reservationRepository->findOneBy(['id' => $request->request->all()['id']]);
            if (isset($request->request->all()['status']) == 'on'){
            $reservation->setStatus('1');
            }else{
                $reservation->setStatus('0');
            }
            $reservation->setDatecheckin(new \DateTime($request->request->all()['checkin']));
            $reservation->setDatecheckout(new \DateTime($request->request->all()['checkout']));
            $reservation->setDescription($request->request->all()['description']);
            if (isset($request->request->all()['allday']) == 'on') {
                $reservation->setAllDay('1');
            }else{
                $reservation->setAllDay('0');
            }
            $reservation->setBackgroundColor($request->request->all()['backgroundColor']);
            $reservation->setBorderColor($request->request->all()['borderColor']);
            $reservation->setTextColor($request->request->all()['textColor']);

            try {
                $this->manager->flush();
                $this->addFlash('success', 'La réservation a bien été modifiée');
            } catch (\Exception $e) {
                $this->addFlash('error', 'Une erreur est survenue lors de la modification de la réservation');
            }
            return $this->redirectToRoute('app_admin_reservation');
        }

        return $this->render('admin/view/reservation.html.twig', [
            'reservations' => $reservations,
            'form' => $form->createView(),
        ]);
    }

    #[Route('/user', name: 'app_admin_user')]
    public function user(Request $request): Response
    {
        $users = $this->userRepository->findAll();

        $users = $this->paginator->paginate(
            $users,
            $request->query->getInt('page', 1),
            20
        );
        $form = $this->createForm(AdminUserType::class);
        $form->handleRequest($request);


        if ($form->isSubmitted() && $form->isValid()) {
            $user = $form->getData();
            $this->manager->persist($user);
            try {
                $this->manager->flush();
                $this->addFlash('success', 'L\'utilisateur a bien été ajouté');
            } catch (\Exception $e) {
                $this->addFlash('error', 'Une erreur est survenue lors de l\'ajout de l\'utilisateur');
            }
            return $this->redirectToRoute('app_admin_user');
        }

     	if ($request->isMethod('POST') && isset($request->request->all()['id'])){
            $user = $this->userRepository->findOneBy(['id' => $request->request->all()['id']]);
            $user->setEmail($request->request->all()['email']);
            $user->setFirstname($request->request->all()['firstname']);
            $user->setLastname($request->request->all()['lastname']);
            $user->setAdress($request->request->all()['adress']);
            $user->setCity($request->request->all()['city']);
            $user->setPhone($request->request->all()['phone']);
            if ( isset($request->request->all()['isVerified'])) {
                $user->setIsVerified('1');
            }else{
                $user->setIsVerified('0');
            }
            if ( isset($request->request->all()['roles'])){
                $user->setRoles((array)'ROLE_ADMIN');
            }else{
                $user->setRoles((array)'ROLE_USER');
            }

            $this->manager->persist($user);
            $this->manager->persist($user);
            try {
                $this->manager->flush();
                $this->addFlash('success', 'L\'utilisateur a bien été modifié');
            } catch (\Exception $e) {
                $this->addFlash('error', 'Une erreur est survenue lors de la modification de l\'utilisateur');
            }
            return $this->redirectToRoute('app_admin_user');
        }

        return $this->render('admin/view/user.html.twig', [
            'users' => $users,
            'form' => $form->createView(),
        ]);
    }
}