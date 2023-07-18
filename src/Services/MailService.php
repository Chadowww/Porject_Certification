<?php

namespace App\Services;

use App\Entity\Book;
use App\Entity\Reservation;
use App\Entity\User;
use Symfony\Bridge\Twig\Mime\TemplatedEmail;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Mailer\Exception\TransportExceptionInterface;
use Symfony\Component\Mailer\MailerInterface;
use Symfony\Component\Mime\Part\DataPart;

class MailService extends AbstractController
{
 	public function __construct(MailerInterface $mailer)
    {
        $this->mailer = $mailer;
    }


    /**
     * @throws TransportExceptionInterface
     */
    public function mailAlerte(Book $book): void
    {
        $email = (new TemplatedEmail())
            ->from($_ENV['MAILER_INFO'])
            ->to($_ENV['MAILER_ADMIN'])
            ->subject('Rupture de stock')
            ->html($this->renderView('mail/mailAlerte.html.twig', [
                'book' => $book,
            ]));


        $this->mailer->send($email);
    }

    public function mailReservation(Book $book, User $user, Reservation $reservation): void
    {
        $date['dateCheckin'] = $reservation->getDatecheckin()->format('d/m/Y');
        $date['dateCheckout'] = $reservation->getDatecheckout()->format('d/m/Y');

        $email = (new TemplatedEmail())
            ->from($_ENV['MAILER_INFO'])
            ->to($_ENV['MAILER_ADMIN'])
            ->subject('Réservation de livre')
            ->html($this->renderView('mail/mailReservation.html.twig', [
                'book' => $book,
                'user' => $user,
                'date' => $date
            ]));

        $this->mailer->send($email);
    }

}