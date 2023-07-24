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

#[Route('/admin')]
#[IsGranted('ROLE_ADMIN')]
class AdminController extends AbstractController
{

    #[Route('/', name: 'app_admin')]
    public function index(): Response
    {
        return $this->render('admin/index.html.twig');
    }

    #[Route('/author', name: 'app_admin_author', methods: ['GET', 'POST'])]
    public function Author(AuthorRepository $authorRepository, Request $request, EntityManagerInterface $manager, PaginatorInterface $paginator):
    Response
    {
        $authors = $authorRepository->findAll();

        $authors = $paginator->paginate(
            $authors,
            $request->query->getInt('page', 1),
            20
        );

        $form = $this->createForm(AuthorType::class);
        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()) {
            $author = $form->getData();
            $manager->persist($author);
            try {
                $manager->flush();
                $this->addFlash('success', 'L\'auteur a bien été ajouté');
            } catch (\Exception $e) {
                $this->addFlash('error', 'Une erreur est survenue lors de l\'ajout de l\'auteur');
            }
            return $this->redirectToRoute('app_admin_author');
        }

        if ($request->isMethod('POST') && $request->request->all()['id'] !== null){
            $author = $authorRepository->findOneBy(['id' => $request->request->all()['id']]);
            $author->setName($request->request->all()['name']);
            $author->setBiography($request->request->all()['biography']);
            $author->setAvatar($request->request->all()['avatar']);

            $manager->persist($author);
            try {
                $manager->flush();
                $this->addFlash('success', 'L\'auteur a bien été modifié');
            } catch (\Exception $e) {
                $this->addFlash('error', 'Une erreur est survenue lors de la modification de l\'auteur');
            }
            return $this->redirectToRoute('app_admin_author');
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
    public function book(BookRepository $bookRepository, Request $request, EntityManagerInterface $manager, PaginatorInterface $paginator):
    Response
    {
        $books = $bookRepository->findAll();

        $books = $paginator->paginate(
            $books,
            $request->query->getInt('page', 1),
            20
        );

        $form = $this->createForm(BookType::class);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $book = $form->getData();
            $manager->persist($book);
            try {
                $manager->flush();
                $this->addFlash('success', 'Le livre a bien été ajouté');
            } catch (\Exception $e) {
                $this->addFlash('error', 'Une erreur est survenue lors de l\'ajout du livre');
            }
            return $this->redirectToRoute('app_admin_book');
        }

        if ($request->isMethod('POST') && $request->request->all()['id'] !== null){
			$book = $bookRepository->findOneBy(['id' => $request->request->all()['id']]);
            $book->setTitle($request->request->all()['title']);
            $book->setDescription($request->request->all()['description']);
            $book->setPublish(new \DateTime($request->request->all()['publish']));
            $book->setQteStock($request->request->all()['qteStock']);
            $book->setQteCheckout($request->request->all()['qteCheckout']);
            $book->setCover($request->request->all()['cover']);
            $manager->persist($book);
            try {
                $manager->flush();
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
    public function borrow(BorrowRepository $borrowRepository, Request $request, EntityManagerInterface $manager, PaginatorInterface $paginator):
    Response
    {
        $borrows = $borrowRepository->findAll();

        $borrows = $paginator->paginate(
            $borrows,
            $request->query->getInt('page', 1),
            20
        );

        $form = $this->createForm(BorrowType::class);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $borrow = $form->getData();
            $manager->persist($borrow);
            try {
                $manager->flush();
                $this->addFlash('success', 'L\'emprunt a bien été créé');
            } catch (\Exception $e) {
                $this->addFlash('error', 'Une erreur est survenue lors de l\'ajout de l\'emprunt');
            }
            return $this->redirectToRoute('app_admin_borrow');
        }

        if ($request->isMethod('POST') && $request->request->all()['id'] !== null){
            $borrow = $borrowRepository->findOneBy(['id' => $request->request->all()['id']]);
            $borrow->setCheckin(new \DateTime($request->request->all()['checkin']));
            $borrow->setCheckout(new \DateTime($request->request->all()['checkout']));

            $manager->persist($borrow);
            try {
                $manager->flush();
                $this->addFlash('success', 'L\'emprunt a bien été modifié');
            } catch (\Exception $e) {
                $this->addFlash('error', 'Une erreur est survenue lors de la modification de l\'emprunt');
            }
            return $this->redirectToRoute('app_admin_borrow');
        }

        return $this->render('admin/view/borrow.html.twig', [
            'borrows' => $borrows,
            'form' => $form->createView(),
        ]);
    }

    #[Route('/category', name: 'app_admin_category')]
    public function category(CategoryRepository $categoryRepository, Request $request, EntityManagerInterface $manager, PaginatorInterface $paginator):
    Response
    {
        $categories = $categoryRepository->findAll();

        $categories = $paginator->paginate(
            $categories,
            $request->query->getInt('page', 1),
            20
        );

        $form = $this->createForm(CategoryType::class);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $category = $form->getData();
            $manager->persist($category);
            try {
                $manager->flush();
                $this->addFlash('success', 'La catégorie a bien été ajoutée');
            } catch (\Exception $e) {
                $this->addFlash('error', 'Une erreur est survenue lors de l\'ajout de la catégorie');
            }
            return $this->redirectToRoute('app_admin_category');
        }

		if ($request->isMethod('POST') && $request->request->all()['id'] !== null){
            $category = $categoryRepository->findOneBy(['id' => $request->request->all()['id']]);
            $category->setName($request->request->all()['name']);
            $manager->persist($category);
            try {
                $manager->flush();
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
    public function comment(CommentRepository $commentRepository, Request $request, EntityManagerInterface $manager, PaginatorInterface $paginator): Response
    {
        $comments = $commentRepository->findAll();

        $comments = $paginator->paginate(
            $comments,
            $request->query->getInt('page', 1),
            20
        );

		if ($request->isMethod('POST')){
            $comment = $commentRepository->findOneBy(['id' => $request->request->all()['id']]);
            $comment->setComment($request->request->all()['comment']);
            $comment->setNote($request->request->all()['note']);
            $manager->persist($comment);
            try {
                $manager->flush();
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
    public function editor(EditorRepository $editorRepository, Request $request, EntityManagerInterface $manager, PaginatorInterface $paginator):
    Response
    {
        $editors = $editorRepository->findAll();

        $editors = $paginator->paginate(
            $editors,
            $request->query->getInt('page', 1),
            20
        );

  		if ($request->isMethod('POST')){
            $editor = $editorRepository->findOneBy(['id' => $request->request->all()['id']]);
            $editor->setName($request->request->all()['name']);

            $manager->persist($editor);
            try {
                $manager->flush();
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
    public function reservation(
        ReservationRepository  $reservationRepository,
        Request                $request,
        EntityManagerInterface $manager,
        PaginatorInterface     $paginator
    ): Response
    {
        $reservations = $reservationRepository->findAll();

        $reservations = $paginator->paginate(
            $reservations,
            $request->query->getInt('page', 1),
            20
        );
        $form = $this->createForm(AdminReservationType::class);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $reservation = $form->getData();
            $manager->persist($reservation);
            try {
                $manager->flush();
                $this->addFlash('success', 'La réservation a bien été ajoutée');
            } catch (\Exception $e) {
                $this->addFlash('error', 'Une erreur est survenue lors de l\'ajout de la réservation');
            }
            return $this->redirectToRoute('app_admin_reservation');
        }

        if ($request->isMethod('POST') && $request->request->all()['id'] !== null){
            $reservation = $reservationRepository->findOneBy(['id' => $request->request->all()['id']]);
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
                $manager->flush();
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
    public function user(UserRepository $userRepository, Request $request, EntityManagerInterface $manager, PaginatorInterface $paginator): Response
    {
        $users = $userRepository->findAll();

        $users = $paginator->paginate(
            $users,
            $request->query->getInt('page', 1),
            20
        );
        $form = $this->createForm(AdminUserType::class);
        $form->handleRequest($request);


        if ($form->isSubmitted() && $form->isValid()) {
            $user = $form->getData();
            $manager->persist($user);
            try {
                $manager->flush();
                $this->addFlash('success', 'L\'utilisateur a bien été ajouté');
            } catch (\Exception $e) {
                $this->addFlash('error', 'Une erreur est survenue lors de l\'ajout de l\'utilisateur');
            }
            return $this->redirectToRoute('app_admin_user');
        }

     	if ($request->isMethod('POST') && isset($request->request->all()['id'])){
            $user = $userRepository->findOneBy(['id' => $request->request->all()['id']]);
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

            $manager->persist($user);
            try {
                $manager->flush();
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