<?php

namespace App\Repository;

use App\Entity\Customer;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @extends ServiceEntityRepository<Customer>
 */
class CustomerRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Customer::class);
    }

    /**
     * Find customers by optional search term across name, email, or company.
     * Returns latest created first by id desc for predictability.
     *
     * @return Customer[]
     */
    public function findBySearch(?string $term): array
    {
        $qb = $this->createQueryBuilder('c');

        if ($term !== null && $term !== '') {
            $like = '%' . addcslashes($term, '%_') . '%';
            $qb->andWhere('c.name LIKE :like OR c.email LIKE :like OR c.company LIKE :like')
               ->setParameter('like', $like);
        }

        return $qb
            ->orderBy('c.id', 'DESC')
            ->getQuery()
            ->getResult();
    }
}


