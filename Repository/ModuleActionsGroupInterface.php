<?php

namespace Bacon\Bundle\AclBundle\Repository;

use FOS\UserBundle\Model\GroupInterface;

/**
 * Interface ModuleActionsGroupInterface
 *
 * @package Bacon\Bundle\AclBundle\Repository
 * @author Adan Felipe Medeiros <adan.grg@gmail.com>
 * @version 1.0
 */
interface ModuleActionsGroupInterface
{
    /**
     * @param string $module
     * @param string $action
     */
    public function hasAuthorization($module, $action, GroupInterface $group);

}
