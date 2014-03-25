<?php
namespace RoundZero\Service;

use Doctrine\ORM\EntityManager;
use RoundZero\Entity\Group as GroupEntity;
use RoundZero\Entity\User as UserEntity;

class User
{
    /**
     * @var EntityManager
     */
    protected $entityManager;

    public function __construct(EntityManager $entityManager)
    {
        $this->entityManager = $entityManager;
    }

    public function getTotalReceivedFromGroup(UserEntity $user, GroupEntity $group)
    {
        $query = $this->entityManager->createQuery(
            'SELECT COUNT(u.id) 
            FROM RoundZero\Entity\Round r 
            JOIN r.recipients u
            WHERE u = :user
            AND r.group = :group'
        );
        $query->setParameter('user', $user);
        $query->setParameter('group', $group);
        return $query->getSingleScalarResult();
    }

    public function getTotalMadeForGroup(UserEntity $user, GroupEntity $group)
    {
        $query = $this->entityManager->createQuery(
            'SELECT COUNT(u.id) 
            FROM RoundZero\Entity\Round r 
            JOIN r.recipients u
            WHERE r.creator = :creator
            AND r.group = :group'
        );
        $query->setParameter('creator', $user);
        $query->setParameter('group', $group);
        return $query->getSingleScalarResult();
    }

    public function getStats(UserEntity $user, GroupEntity $group)
    {
        $made = $this->getTotalMadeForGroup($user, $group);
        $received = $this->getTotalReceivedFromGroup($user, $group);

        return array(
            'made' => $made,
            'received' => $received,
            'balance' => $made - $received,
        );
    }
}
