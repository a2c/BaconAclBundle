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
            'slug' => $entity->getSlug(),
            'name' => $entity->getName(),
            'id' => $entity->getId(),
        ];

        if (!empty($data['name'])) {
            $queryBuilder
                ->andWhere($queryBuilder->expr()->like('m.name', ':name'))
                ->setParameter('name', "%{$data['name']}%")
            ;
        }

        if (!empty($data['slug'])) {
            $queryBuilder
                ->andWhere($queryBuilder->expr()->like('m.slug', ':slug'))
                ->setParameter('slug', "%{$data['slug']}%")
            ;
        }

        if (!empty($data['id'])) {
            $queryBuilder
                ->andWhere($queryBuilder->expr()->eq('m.id', ':id'))
                ->setParameter('id', $data['id'])
            ;
        }

        return $queryBuilder
            ->orderBy('m.'.$sort, $direction)
            ->getQuery()
        ;
    }
}
