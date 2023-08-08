<?php

namespace App\Controller;

use App\Entity\User;
use App\Form\UserType;
use App\Repository\ReservationRepository;
use App\Repository\UserRepository;
use Psr\Container\ContainerExceptionInterface;
use Psr\Container\NotFoundExceptionInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;
use Symfony\Component\Routing\Annotation\Route;

#[Route('/user')]
class UserController extends AbstractController
{
    #[Route('/', name: 'app_user_index', methods: ['GET'])]
    public function index(UserRepository $userRepository): Response
    {
        return $this->render('user/index.html.twig', [
            'users' => $userRepository->findAll(),
        ]);
    }

    #[Route('/new', name: 'app_user_new', methods: ['GET', 'POST'])]
    public function new(Request $request, UserRepository $userRepository): Response
    {
        $user = new User();
        $form = $this->createForm(UserType::class, $user);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $userRepository->save($user, true);

            return $this->redirectToRoute('app_user_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->renderForm('user/new.html.twig', [
            'user' => $user,
            'form' => $form,
        ]);
    }

    #[Route('/{id}', name: 'app_user_show', methods: ['GET'])]
    public function show(User $user, ReservationRepository $reservationRepository): Response
    {
        $events = $reservationRepository->findBy(['user' => $user]);

        if (in_array('ROLE_ADMIN', $user->getRoles(), true)) {
            $events = $reservationRepository->findAll();
        }

        $rdv = [];
		$data = [];
        foreach ($events as $event) {
            $rdv[] = [
                'id' => $event->getId(),
                'end' => $event->getDatecheckout()->format('Y-m-d H:i:s'),
                'start' => $event->getDatecheckin()->format('Y-m-d H:i:s'),
                'title' => $event->getBook()->getTitle(),
                'backgroundColor' => $event->getBackgroundColor(),
                'borderColor' => $event->getBorderColor(),
                'textColor' => $event->getTextColor(),
                'allDay' => $event->isAllDay()
            ];
            $data = json_encode($rdv);
        }
        return $this->render('user/show.html.twig', [
            'user' => $user,
            'data' => $data,
        ]);
    }

    #[Route('/mon-profile/{id}', name: 'app_user_edit', methods: ['GET', 'POST'])]
    public function edit(Request $request, User $user, UserRepository $userRepository): Response
    {
        $form = $this->createForm(UserType::class, $user);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $userRepository->save($user, true);

            $this->addFlash('success', 'Votre profil a bien été modifié');
            return $this->redirectToRoute('app_user_show', ['id' => $user->getId()], Response::HTTP_SEE_OTHER);
        }

        return $this->render('user/edit.html.twig', [
            'user' => $user,
            'form' => $form,
        ]);
    }

    /**
     * @throws ContainerExceptionInterface
     * @throws NotFoundExceptionInterface
     */
    #[Route('/{id}', name: 'app_user_delete', methods: ['POST'])]
    public function delete(Request $request, User $user, UserRepository $userRepository): Response
    {
        if ($this->isCsrfTokenValid('delete'.$user->getId(), $request->request->get('_token'))) {
            $userRepository->remove($user, true);
        }

        $request->getSession()->invalidate();
        $this->container->get('security.token_storage')->setToken(null);

		$this->addFlash('success', 'Votre compte a bien été supprimé');
        return $this->redirectToRoute('app_admin_user', [], Response::HTTP_SEE_OTHER);
    }

    #[Route('/{id}/update/password', name: 'app_user_update_password', methods: ['POST'])]
    public function updatePassword(User $user, UserRepository $userRepository, Request $request, UserPasswordHasherInterface $userPasswordHasher): Response
    {
        if ($request->isMethod('POST')) {
            $password = $request->request->get('password');
            $confirmPassword = $request->request->get('confirm-password');
            if ($password != $confirmPassword) {
                $this->addFlash('error', 'Les mots de passe ne sont pas identiques');
                return $this->redirectToRoute('app_user_edit', ['id' => $user->getId()], Response::HTTP_SEE_OTHER);
            } else {
                try {
                    $user->setPassword(
                        $userPasswordHasher->hashPassword(
                            $user,
                            $request->request->get('password')
                        )
                    );
                    $userRepository->save($user, true);
                    $this->addFlash('success', 'Votre mot de passe a bien été modifié');
                    return $this->redirectToRoute('app_user_edit', ['id' => $user->getId()], Response::HTTP_SEE_OTHER);
                } catch (\Exception $e) {
                    $this->addFlash('error', 'Une erreur est survenue lors de la modification de votre mot de passe');
                }
            }
        }
    }
    #[Route('/{id}/favorites', name: 'app_user_show_favorites', methods: ['GET'])]
    public function showFavorites(User $user, UserRepository $userRepository): Response
    {
        return $this->render('user/show_favorites.html.twig', [
            'user' => $user,
        ]);
    }
}
