<?php

namespace App\Repository;

use App\Entity\FavoriteMovies;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Common\Collections\Expr\Value;
use Doctrine\ORM\QueryBuilder;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @method FavoriteMovies|null find($id, $lockMode = null, $lockVersion = null)
 * @method FavoriteMovies|null findOneBy(array $criteria, array $orderBy = null)
 * @method FavoriteMovies[]    findAll()
 * @method FavoriteMovies[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class FavoriteMoviesRepository extends ServiceEntityRepository
{
    /**
     * Items per page.
     *
     * Use constants to define configuration options that rarely change instead
     * of specifying them in app/config/config.yml.
     * See https://symfony.com/doc/current/best_practices.html#configuration
     *
     * @constant int
     */
    const PAGINATOR_ITEMS_PER_PAGE = 10;

    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, FavoriteMovies::class);
    }

    /**
     * Query all records.
     *
     * @return \Doctrine\ORM\QueryBuilder Query builder
     */
    public function queryAll(int $id): QueryBuilder
    {
        return $this->getOrCreateQueryBuilder()
            ->select('favorite_movies', 'films')
            ->leftJoin('favorite_movies.id_film','films')
            ->andWhere('favorite_movies.id_user = :user')
            ->setParameter('user', $id)
            ->orderBy('favorite_movies.id', 'DESC');
    }

    /**
     * Get or create new query builder.
     *
     * @param \Doctrine\ORM\QueryBuilder|null $queryBuilder Query builder
     *
     * @return \Doctrine\ORM\QueryBuilder Query builder
     */
    private function getOrCreateQueryBuilder(QueryBuilder $queryBuilder = null): QueryBuilder
    {
        return $queryBuilder ?: $this->createQueryBuilder('favorite_movies');
    }

    /**
     * FindByExampleField.
     *
     * @param Value $value
     *
     * @return FavoriteMovies[] Returns an array of Films objects
     */
    public function findByExampleField($value)
    {
        return $this->createQueryBuilder('l')
            ->andWhere('l.exampleField = :val')
            ->setParameter('val', $value)
            ->orderBy('l.id', 'ASC')
            ->setMaxResults(10)
            ->getQuery()
            ->getResult()
            ;
    }

    /**
     * Save record.
     *
     * @param \App\Entity\FavoriteMovies $favorite Tag entity
     *
     * @throws \Doctrine\ORM\ORMException
     * @throws \Doctrine\ORM\OptimisticLockException
     */
    public function save(FavoriteMovies $favorite): void
    {
        $this->_em->persist($favorite);
        $this->_em->flush($favorite);
    }

    /**
     * Delete record.
     *
     * @param \App\Entity\FavoriteMovies $favorite FavoriteMovies entity
     *
     * @throws \Doctrine\ORM\ORMException
     * @throws \Doctrine\ORM\OptimisticLockException
     */
    public function delete(FavoriteMovies $favorite)
    {
        $this->_em->remove($favorite);
        $this->_em->flush($favorite);
    }
}
