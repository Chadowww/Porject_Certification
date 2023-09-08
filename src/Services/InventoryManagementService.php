<?php

namespace App\Services;

use App\Entity\Book;
use App\Entity\User;
use Symfony\Component\Mailer\Exception\TransportExceptionInterface;

class InventoryManagementService
{
    private MailService $mailService;

    public function __construct(MailService $mailService)
    {
        $this->mailService = $mailService;
    }

    /**
     * @throws TransportExceptionInterface
     */
    public function verifStock(Book $book, User $user): bool
    {
        if ($book->getQteStock() > 0) {
            $book->setQteStock($book->getQteStock() - 1);
            $book->setQteCheckout($book->getQteCheckout() + 1);

            if ($book->getQteStock() == 0) {
            	$this->mailService->mailAlerte($book);
            }
            return true;
        } else {
            return false;
        }
    }
}