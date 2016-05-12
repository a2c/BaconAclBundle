<?php

namespace Bacon\Bundle\AclBundle\Repository;


use Bacon\Bundle\AclBundle\Model\ModuleActionsInterface;

/**
 * Interface ModuleActionsRepositoryInterface
 * 
 * @package Bacon\Bundle\AclBundle\Repository
 * @author Adan Felipe Medeiros <adan.grg@gmail.com>
 *
 * @version 1.0
 */
interface ModuleActionsRepositoryInterface
{
    public function getQueryPagination(ModuleActionsInterface $entity, $sort, $direction);
}