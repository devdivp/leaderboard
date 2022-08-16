<?php

namespace App\Entity;

use Doctrine\ORM\EntityRepository;

/**
 * UserRepository
 */
class UserRepository extends EntityRepository
{
    /**
     * Gets Leaderboard Data
     *
     * @return array
     */
    public function getLeaderboardData(): array
    {
        $dql = "SELECT u.id, u.name, u.points FROM App\Entity\User u ORDER BY u.points DESC";
        return $this->getEntityManager()->createQuery($dql)
            ->getArrayResult();
    }
}
