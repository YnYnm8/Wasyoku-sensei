<?php

namespace App\Repository;

use App\Entity\AsianShop;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

class AsianShopRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, AsianShop::class);
    }

    /**
     * 郵便番号の前方一致で店舗を検索する（例：'310'と入力すると31000〜31999にマッチ）
     *
     * @return AsianShop[]
     */
    public function findByPostcodePrefix(string $postcodePrefix): array
    {
        return $this->createQueryBuilder('a')
            ->andWhere('a.postcode LIKE :prefix')
            ->setParameter('prefix', $postcodePrefix . '%')
            ->orderBy('a.postcode', 'ASC')
            ->getQuery()
            ->getResult();
    }
}