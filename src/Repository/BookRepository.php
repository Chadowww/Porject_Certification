<?php

namespace App\Repository;

use App\Entity\Book;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @extends ServiceEntityRepository<Book>
 *
 * @method Book|null find($id, $lockMode = null, $lockVersion = null)
 * @method Book|null findOneBy(array $criteria, array $orderBy = null)
 * @method Book[]    findAll()
 * @method Book[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class BookRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Book::class);
    }

    public function save(Book $entity, bool $flush = false): void
    {
        $this->getEntityManager()->persist($entity);

        if ($flush) {
            $this->getEntityManager()->flush();
        }
    }

    public function remove(Book $entity, bool $flush = false): void
    {
        $this->getEntityManager()->remove($entity);

        if ($flush) {
            $this->getEntityManager()->flush();
        }
    }
    public function searchBook(array $credentials): array
    {
        $credential = implode(' ', $credentials);
        $query = $this->createQueryBuilder('b')
            ->leftJoin('b.author', 'a');
        if ($credentials != null) {
            $query->where('MATCH_AGAINST(b.title, b.description) AGAINST(:words boolean) > 0')
                ->orWhere('MATCH_AGAINST(a.name) AGAINST(:words boolean) > 0')
                ->setParameter('words', $credential);
        }
        return $query->getQuery()->getResult();
    }

    public function mostFavoriteBooks(): array
    {
        $query = $this->createQueryBuilder('b')
			->select('b', 'COUNT(u.id) AS HIDDEN total')
            ->innerJoin('b.users', 'u')
            ->groupBy('b.id')
            ->setMaxResults(10)
            ->orderBy('total', 'DESC')
            ->getQuery();
        return $query->getResult();
    }

    public function lastBooks(): array
    {
        $query = $this->createQueryBuilder('b')
            ->orderBy('b.id', 'DESC')
            ->setMaxResults(10)
            ->getQuery();
        return $query->getResult();
    }

    public function randBooks(): array
    {
        $query = $this->createQueryBuilder('b')
            ->orderBy('RAND()')
            ->setMaxResults(10)
            ->getQuery();

        return $query->getResult();
    }

//    /**
//     * @return Book[] Returns an array of Book objects
//     */
//    public function findByExampleField($value): array
//    {
//        return $this->createQueryBuilder('b')
//            ->andWhere('b.exampleField = :val')
//            ->setParameter('val', $value)
//            ->orderBy('b.id', 'ASC')
//            ->setMaxResults(10)
//            ->getQuery()
//            ->getResult()
//        ;
//    }

//    public function findOneBySomeField($value): ?Book
//    {
//        return $this->createQueryBuilder('b')
//            ->andWhere('b.exampleField = :val')
//            ->setParameter('val', $value)
//            ->getQuery()
//            ->getOneOrNullResult()
//        ;
//    }
}
