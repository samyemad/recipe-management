<?php

namespace App\Repository\Ingredient;

use App\Entity\Ingredient\Ingredient;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @extends ServiceEntityRepository<Ingredient>
 */
class IngredientRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Ingredient::class);
    }

    public function findWithPagination(int $limit, int $offset): array
    {
        return $this->createQueryBuilder('r')
            ->setMaxResults($limit)
            ->setFirstResult($offset)
            ->getQuery()
            ->getResult();
    }
}
