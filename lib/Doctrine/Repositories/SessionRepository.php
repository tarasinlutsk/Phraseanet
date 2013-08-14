<?php

/*
 * This file is part of Phraseanet
 *
 * (c) 2005-2013 Alchemy
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Repositories;

use Alchemy\Phrasea\Application;
use Doctrine\ORM\EntityRepository;
use Entities\Feed;

/**
 * SessionRepository
 *
 * This class was generated by the Doctrine ORM. Add your own custom
 * repository methods below.
 */
class SessionRepository extends EntityRepository
{
    /**
     * Returns all the feeds a user can access.
     *
     * @param  User_Adapter                            $user
     * @return \Doctrine\Common\Collections\Collection
     */
    public function getAllForUser(\User_Adapter $user)
    {
        $base_ids = array_keys($user->ACL()->get_granted_base());

        $qb = $this->createQueryBuilder('f');
        $qb->where($qb->expr()->isNull('f.baseId'))
            ->orWhere('f.public = true');

        if (!empty($base_ids)) {
            $qb->orWhere($qb->expr()->in('f.baseId', $base_ids));
        }

        $qb->orderBy('f.updatedOn', 'DESC');

        return $qb->getQuery()->getResult();
    }

    /**
     * Returns all the feeds from a given array containing their id.
     *
     * @param  array      $feed_id
     * @return Collection
     */
public function findByIds(array $feedIds)
{
    $qb = $this->createQueryBuilder('f');

    if (!empty($feedIds)) {
        $qb->Where($qb->expr()->in('f.id', $feedIds));
    }

    $qb->orderBy('f.updated_on', 'DESC');

    return $qb->getQuery()->getResult();
}
}
