<?php
/*
 * Films Repository
 */

namespace App\Repository;

use App\Entity\Category;
use App\Entity\Films;
use App\Entity\Tag;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Common\Collections\Expr\Value;
use Doctrine\ORM\OptimisticLockException;
use Doctrine\ORM\ORMException;
use Doctrine\ORM\QueryBuilder;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @method Films|null find($id, $lockMode = null, $lockVersion = null)
 * @method Films|null findOneBy(array $criteria, array $orderBy = null)
 * @method Films[]    findAll()
 * @method Films[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class FilmsRepository extends ServiceEntityRepository
{
    /**
     * FilmsRepository constructor.
     */
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Films::class);
    }

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
     * Query all records.
     *
     * @param array $filters Filters array
     *
     * @return QueryBuilder Query builder
     */
    public function queryAll(array $filters = []): QueryBuilder
    {
        $queryBuilder = $this->getOrCreateQueryBuilder()
            ->select(
                'partial film.{id, title}',
                'partial category.{id, name}'
            )
            ->join('film.category', 'category')

            ->orderBy('film.title', 'DESC');
        $queryBuilder = $this->applyFiltersToList($queryBuilder, $filters);

        return $queryBuilder;
    }

    /**
     * FindByExampleField.
     *
     * @param Value $value
     *
     * @return Films[] Returns an array of Films objects
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
     * @param Films $film Tag entity
     *
     * @throws ORMException
     * @throws OptimisticLockException
     */
    public function save(Films $film): void
    {
        $this->_em->persist($film);
        $this->_em->flush($film);
    }

    /**
     * Query Films by name.
     *
     * @param null $id
     *
     * @return int|mixed|string
     */
    public function queryById($id = null)
    {
        $queryBuilder = $this->queryAll();

        if (!is_null($id)) {
            $queryBuilder->andWhere('l.id LIKE :id')
                ->setParameter('id', '%'.$id.'%');
        }

        return $queryBuilder->getQuery()->execute();
    }

    /**
     * Query  by id.
     *
     * @param null $id
     *
     * @return QueryBuilder Query builder
     */
    public function queryById1($id = null): QueryBuilder
    {
        $queryBuilder = $this->queryAll();

        if (!is_null($id)) {
            $queryBuilder->andWhere('l.id LIKE :id')
                ->setParameter('id', '%'.$id.'%');
        }

        return $queryBuilder;
    }

    /**
     * Delete record.
     *
     * @param Films $film Film entity
     *
     * @throws ORMException
     * @throws OptimisticLockException
     */
    public function delete(Films $film)
    {
        $this->_em->remove($film);
        $this->_em->flush($film);
    }

    /**
     * Query film by name.
     *
     * @param null $title
     *
     * @return QueryBuilder Query builder
     */
    public function queryByTitle($title = null): QueryBuilder
    {
        $queryBuilder = $this->queryAll();

        if (!is_null($title)) {
            $queryBuilder->andWhere('film.title LIKE :title')
                ->setParameter('title', '%'.$title.'%');
        }

        return $queryBuilder;
    }

    /**
     * Apply filters to paginated list.
     *
     * @param QueryBuilder $queryBuilder Query builder
     * @param array        $filters      Filters array
     *
     * @return QueryBuilder Query builder
     */
    private function applyFiltersToList(QueryBuilder $queryBuilder, array $filters = []): QueryBuilder
    {
        if (isset($filters['category']) && $filters['category'] instanceof Category) {
            $queryBuilder->andWhere('category = :category')
                ->setParameter('category', $filters['category']);
        }

        if (isset($filters['tag']) && $filters['tag'] instanceof Tag) {
            $queryBuilder->andWhere('tags IN (:tag)')
                ->setParameter('tag', $filters['tag']);
        }

        return $queryBuilder;
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
        return $queryBuilder ?: $this->createQueryBuilder('film');
    }
}
