<?php

namespace Bacon\Bundle\AclBundle\Repository;

use FOS\UserBundle\Model\GroupInterface;

/**
 * Class HasAuthorationRepository
 * @package Bacon\Bundle\AclBundle\Repository
 * @author Adan Felipe Medeiros <adan.grg@gmail.com>
 * @version 1.0
 */
trait HasAuthorationRepository
{
    /**
     * @param $module
     * @param $action
     * @param GroupInterface $group
     * 
     * @return mixed
     */
    public function hasAuthorization($module, $action, GroupInterface $group)
    {
        $queryBuilder = $this->createQueryBuilder('mag');

        $queryBuilder->select('mag.id');

        $queryBuilder->innerJoin('mag.group', 'g');
        $queryBuilder->innerJoin('mag.module', 'm');
        $queryBuilder->innerJoin('mag.moduleActions', 'ma');

        $queryBuilder->andWhere(
            $queryBuilder->expr()->eq('m.name', ':module')
        );

        $queryBuilder->andWhere(
            $queryBuilder->expr()->eq('ma.identifier', ':action')
        );

        $queryBuilder->andWhere(
            $queryBuilder->expr()->eq('mag.group', ':group')
        );

        $queryBuilder->andWhere(
            $queryBuilder->expr()->eq('mag.enabled', ':enabled')
        );

        $queryBuilder->setParameter('module', $module);
        $queryBuilder->setParameter('action', $action);
        $queryBuilder->setParameter('group', $group);
        $queryBuilder->setParameter('enabled', true);

        return $queryBuilder->getQuery()->getOneOrNullResult();
    }
}