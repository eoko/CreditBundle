<?php

namespace Eoko\CreditBundle\Repository;

use Doctrine\ORM\EntityRepository;

/**
 * Class UserCreditRepository
 * @package Eoko\CreditBundle\Repository
 */
class UserCreditRepository extends EntityRepository
{
    /**
     * @param $ip
     * @param null $user_id
     * @return array
     */
    public function findFromIpOrId($ip, $user_id = null)
    {
        $query = $this->createQueryBuilder('cm')
            ->where('cm.ip = :ip')
            ->setParameter('ip', $ip);

        if ($user_id !== null) {
            $query
                ->orWhere('cm.user_id = :user_id')
                ->setParameter('user_id', $user_id);
        }

        return $query
            ->getQuery()
            ->getOneOrNullResult();
    }
}
