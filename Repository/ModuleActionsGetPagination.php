<?php

namespace Bacon\Bundle\AclBundle\Repository;

use Bacon\Bundle\AclBundle\Model\ModuleActionsInterface;

/**
 * Class ModuleActionsGetPagionation
 * @package Bacon\Bundle\AclBundle\Repository
 * @author Adan Felipe Medeiros
 */
trait ModuleActionsGetPagination
{
    /**
     * Search for records based on an entity
     *
     */
    public function getQueryPagination(ModuleActionsInterface $entity, $sort, $direction)
    {

        $queryBuilder = $this->createQueryBuilder('m');

        $data = [
            'name' => $entity->getName(),
            'identifier' => $entity->getIdentifier(),
            'module'     => !is_null($entity->getModule()) ? $entity->getModule() :  '',
            'id' => $entity->getId(),
        ];

        if (!empty($entity->getModule())) {
            $queryBuilder
                ->andWhere($queryBuilder->expr()->eq('m.module', ':module'))
                ->setParameter('module', $data['module'])
            ;
        }

        if (!empty($data['id'])) {
            $queryBuilder
                ->andWhere($queryBuilder->expr()->eq('m.id', ':id'))
                ->setParameter('id', $data['id'])
            ;
        }

        if (!empty($data['name'])) {
            $queryBuilder
                ->andWhere($queryBuilder->expr()->like('m.name', ':name'))
                ->setParameter('name', "%{$data['name']}%")
            ;
        }

        if (!empty($data['identifier'])) {
            $queryBuilder
                ->andWhere($queryBuilder->expr()->like('m.identifier', ':identifier'))
                ->setParameter('identifier', "%{$data['identifier']}%")
            ;
        }

        return $queryBuilder
            ->orderBy('m.'.$sort, $direction)
            ->getQuery()
            ;
    }
}
