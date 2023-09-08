<?php

namespace App\Controller;

use App\Entity\Reservation;
use App\Form\ReservationType;
use App\Repository\{BookRepository, ReservationRepository};
use App\Services\InventoryManagementService;
use App\Services\MailService;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\{Request, Response};
use Symfony\Component\Routing\Annotation\Route;

#[Route('/reservation')]
class ReservationController extends AbstractController
{
    #[Route('/', name: 'app_reservation_index', methods: ['GET'])]
    public function index(ReservationRepository $reservationRepository): Response
    {
        return $this->render('reservation/index.html.twig', [
            'reservations' => $reservationRepository->findAll(),
        ]);
    }

    #[Route('/new', name: 'app_reservation_new', methods: ['GET', 'POST'])]
    public function new(
        Request $request,
        ReservationRepository $reservationRepository,
        BookRepository $bookRepository,
        InventoryManagementService $managementService,
    	MailService $mailService): Response
    {
        $reservation = new Reservation();
        $form = $this->createForm(ReservationType::class, $reservation);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {

              $user = $this->getUser();
              $book = $bookRepository->find($request->request->get('book'));
              if ($managementService->verifStock($book, $user) ){
                  $reservation->setUser($user);
                  $reservation->setBook($book);

                  if ($reservationRepository->save($reservation, true)){
                      $this->addFlash('success', 'La réservation a bien été enregistrée');
                      $mailService->mailReservation($book, $user, $reservation);
                  }

              }else{
                    $this->addFlash('error', 'Le livre n\'est pour le moment  indisponible');
                    return $this->redirectToRoute('app_book_show', ['id' => $book->getId()],
                        Response::HTTP_SEE_OTHER);
              }


            return $this->redirectToRoute('app_user_show', ['id' => $request->request->get('user')],
                Response::HTTP_SEE_OTHER);
        }

        return $this->renderForm('reservation/new.html.twig', [
            'reservation' => $reservation,
            'form' => $form,
        ]);
    }

    #[Route('/{id}', name: 'app_reservation_show', methods: ['GET'])]
    public function show(Reservation $reservation): Response
    {
        return $this->render('reservation/show.html.twig', [
            'reservation' => $reservation,
        ]);
    }

    #[Route('/{id}/edit', name: 'app_reservation_edit', methods: ['GET', 'POST'])]
    public function edit(Request $request, Reservation $reservation, ReservationRepository $reservationRepository): Response
    {
        $form = $this->createForm(ReservationType::class, $reservation);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $reservationRepository->save($reservation, true);

            return $this->redirectToRoute('app_reservation_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->renderForm('reservation/edit.html.twig', [
            'reservation' => $reservation,
            'form' => $form,
        ]);
    }

    #[Route('/{id}', name: 'app_reservation_delete', methods: ['POST'])]
    public function delete(Request $request, Reservation $reservation, ReservationRepository $reservationRepository): Response
    {
        if ($this->isCsrfTokenValid('delete'.$reservation->getId(), $request->request->get('_token'))) {
            $reservationRepository->remove($reservation, true);
        }
		$this->addFlash('success', 'La réservation a bien été supprimée');
        return $this->redirectToRoute('app_admin_reservation', [], Response::HTTP_SEE_OTHER);
    }
}
