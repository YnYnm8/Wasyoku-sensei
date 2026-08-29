<?php

namespace App\Repository;

use App\Entity\AsianShop;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

class AsianShopRepository extends ServiceEntityRepository
{
    /**
     * Registers this repository for the AsianShop entity.
     */
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, AsianShop::class);
    }

    /**
     * Finds shops whose postcode starts with the given prefix (e.g. '310' matches 31000-31999).
     *
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