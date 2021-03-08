<?php

namespace App\Repository;

use App\Entity\InvoiceHeader;
use App\Entity\Location;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\ORM\QueryBuilder;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @method InvoiceHeader|null find($id, $lockMode = null, $lockVersion = null)
 * @method InvoiceHeader|null findOneBy(array $criteria, array $orderBy = null)
 * @method InvoiceHeader[]    findAll()
 * @method InvoiceHeader[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class InvoiceHeaderRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, InvoiceHeader::class);
    }

    /**
     * @param $data
     * @return QueryBuilder
     */
    public function findAllQb($data): QueryBuilder
    {
        $qb = $this->createQueryBuilder('c')
            ->orderBy('c.date', 'DESC');
        if($data['dateFrom']) {
            $qb->andWhere('(c.date > :dateFrom)')
                ->setParameter('dateFrom', $data['dateFrom']);
        }
        if($data['dateTo']) {
            $qb->andWhere('(c.date < :dateTo)')
                ->setParameter('dateTo', $data['dateTo']);
        }
        if($data['status']) {
            $qb->andWhere('(c.status = :status)')
                ->setParameter('status', $data['status']);
        }
        if($data['location']) {
            $qb->andWhere('(c.location = :location)')
                ->setParameter('location', $data['location']);
        }
        return $qb;
    }

    public function findByLocationGroupedByStatus(Location $location)
    {
        return $this->createQueryBuilder('i')
            ->select('i.status')
            ->addSelect('SUM(il.value) AS total')
            ->innerJoin('i.invoiceLines', 'il')
            ->andWhere('i.location = :location')
            ->setParameter('location', $location)
            ->groupBy('i.status')
            ->orderBy('i.status', 'ASC')
            ->getQuery()
            ->getResult();
    }


}
