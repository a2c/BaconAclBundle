<?php
namespace Bacon\Bundle\AclBundle\Repository;

use Doctrine\ORM\EntityRepository;
use Bacon\Bundle\AclBundle\Entity\Module;

/**
 * Class ModuleRepository
 * @package Bacon\Bundle\AclBundle\Repository
 * @author Adan Felipe Medeiros <adan.grg@gmail.com>
 * @version 1.0
 */
class ModuleRepository extends EntityRepository
{
    /**
    * Search for records based on an entity
    *
    */
    public function getQueryPagination(Module $entity, $sort, $direction)
    {

        $queryBuilder = $this->createQueryBuilder('m');

        $data = [
            'name' => $entity->getName(),
            'id' => $entity->getId(),
            'created_at' => $entity->getCreatedAt(),
            'updated_at' => $entity->getUpdatedAt(),
            'deleted_at' => $entity->getDeletedAt(),
        ];

        if (!empty($data['name'])) {
            $queryBuilder
                ->andWhere($queryBuilder->expr()->like('m.name', ':name'))
                ->setParameter('name', "%{$data['name']}%")
            ;
        }

        if (!empty($data['id'])) {
            $queryBuilder
                ->andWhere($queryBuilder->expr()->eq('m.id', ':id'))
                ->setParameter('id', $data['id'])
            ;
        }

        if (!empty($data['created_at'])) {
            $queryBuilder
                ->andWhere($queryBuilder->expr()->eq('m.created_at', ':created_at'))
                ->setParameter('created_at', $data['created_at'])
            ;
        }

        if (!empty($data['updated_at'])) {
            $queryBuilder
                ->andWhere($queryBuilder->expr()->eq('m.updated_at', ':updated_at'))
                ->setParameter('updated_at', $data['updated_at'])
            ;
        }

        if (!empty($data['deleted_at'])) {
            $queryBuilder
                ->andWhere($queryBuilder->expr()->eq('m.deleted_at', ':deleted_at'))
                ->setParameter('deleted_at', $data['deleted_at'])
            ;
        }

        return $queryBuilder
            ->orderBy('m.'.$sort, $direction)
            ->getQuery()
        ;
    }
}
