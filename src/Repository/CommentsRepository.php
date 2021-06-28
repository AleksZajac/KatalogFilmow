<?php
/*
 *  CommentsRepository
 */

namespace App\Repository;

use App\Entity\Comments;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\ORM\OptimisticLockException;
use Doctrine\ORM\ORMException;
use Doctrine\ORM\QueryBuilder;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @method Comments|null find($id, $lockMode = null, $lockVersion = null)
 * @method Comments|null findOneBy(array $criteria, array $orderBy = null)
 * @method Comments[]    findAll()
 * @method Comments[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class CommentsRepository extends ServiceEntityRepository
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

    /**
     * CategoryRepository constructor.
     * @param ManagerRegistry $registry
     */
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Comments::class);
    }

    /**
     * Query all records.
     *
     * @return QueryBuilder Query builder
     */
    public function queryAll(): QueryBuilder
    {
        return $this->getOrCreateQueryBuilder()
            ->orderBy('l.id', 'DESC');
    }

    /**
     * @param null $id
     *
     * @return QueryBuilder
     */
    public function commentfilms($id = null): QueryBuilder
    {
        $queryBuilder = $this->queryAll();
        if (!is_null($id)) {
            $queryBuilder->select('DISTINCT l.id')
            ->leftJoin('f.films.id', 'f')
                ->andWhere('f.id LIKE :id');
        }

        return $queryBuilder;
    }

    /**
     * Save record.
     *
     * @param Comments $comments Category entity
     *
     * @throws ORMException
     * @throws OptimisticLockException
     */
    public function save(Comments $comments): void
    {
        $this->_em->persist($comments);
        $this->_em->flush($comments);
    }

    /**
     * Delete record.
     *
     * @param Comments $comments Comments entity
     *
     * @throws ORMException
     * @throws OptimisticLockException
     */
    public function delete(Comments $comments)
    {
        $this->_em->remove($comments);
        $this->_em->flush($comments);
    }

    /**
     * Get or create new query builder.
     *
     * @param QueryBuilder|null $queryBuilder Query builder
     *
     * @return QueryBuilder Query builder
     */
    private function getOrCreateQueryBuilder(QueryBuilder $queryBuilder = null): QueryBuilder
    {
        return $queryBuilder ?: $this->createQueryBuilder('l');
    }
}
