<?php

namespace App\Services;

use App\Entity\Book;

class InventoryManagementService
{
	public function verifStock(Book $book): bool
    {
        if ($book->getQteStock() > 0) {
            $book->setQteStock($book->getQteStock() - 1);

//            TODO: 'envoyer un mail à l\'admin pour lui dire de mettre le livre de coté';

            if ($book->getQteStock() == 0) {
//                TODO: 'envoyer un mail à l\'admin pour lui dire qu\'il n\'y a plus de livre en stock';
            }
            return true;
        } else {
            return false;
        }
    }



}