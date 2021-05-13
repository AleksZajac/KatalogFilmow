<?php
/**
 * UsersProfile Repository
 */
namespace App\Repository;

use App\Entity\UsersProfile;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Symfony\Bridge\Doctrine\ManagerRegistry;
use Doctrine\ORM\OptimisticLockException;
use Doctrine\ORM\ORMException;

/**
 * Class UsersProfileRepository
 * @method UsersProfile|null find($id, $lockMode = null, $lockVersion = null)
 * @method UsersProfile|null findOneBy(array $criteria, array $orderBy = null)
 * @method UsersProfile[]    findAll()
 * @method UsersProfile[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class UsersProfileRepository extends ServiceEntityRepository
{
    /**
     * UsersProfileRepository constructor.
     * @param \Doctrine\Persistence\ManagerRegistry $registry
     */
    public function __construct(\Doctrine\Persistence\ManagerRegistry $registry)
    {
        parent::__construct($registry, UsersProfile::class);
    }

    /**
     * Save
     * @param UsersProfile $usersprofile UsersProfile entity
     *
     * @throws ORMException
     * @throws OptimisticLockException
     */
    public function save(UsersProfile $usersprofile):void
    {
        $this->_em->persist($usersprofile);
        $this->_em->flush($usersprofile);
    }
}
