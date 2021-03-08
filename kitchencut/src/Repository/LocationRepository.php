<?php

namespace App\Repository;

use App\Entity\Location;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\ORM\QueryBuilder;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @method Location|null find($id, $lockMode = null, $lockVersion = null)
 * @method Location|null findOneBy(array $criteria, array $orderBy = null)
 * @method Location[]    findAll()
 * @method Location[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class LocationRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Location::class);
    }

    /**
     * @param $data
     * @return QueryBuilder
     */
    public function findAllQb($data): QueryBuilder
    {
        $qb = $this->createQueryBuilder('c')
            ->orderBy('c.name', 'ASC');
        if($data['query']) {
            $qb->where('(c.name LIKE :query)')
                ->setParameter('query', '%' . $data['query'] . '%');
        }
        return $qb;
    }
}
