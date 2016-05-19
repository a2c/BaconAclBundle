<?php

namespace Bacon\Bundle\AclBundle\Model;

/**
 * Interface ModuleActionsGroupInterface
 * @package Bacon\Bundle\AclBundle\Model
 * @author Adan Felipe Medeiros <adan.grg@gmail.com>
 * @version 1.0
 */
interface ModuleActionsGroupInterface
{
    /**
     * @return mixed
     */
    public function getEnabled();

    /**
     * @param boolean $enabled
     * @return ModuleActionsUsers
     */
    public function setEnabled($enabled);

    /**
     * @return Group
     */
    public function getGroup();

    /**
     * @param Group $group
     * @return ModuleActionsUsers
     */
    public function setGroup($group);

    /**
     * @return \Bacon\Bundle\AclBundle\Entity\Module
     */
    public function getModule();

    /**
     * @param \Bacon\Bundle\AclBundle\Entity\Module $module
     * @return ModuleActionsUsers
     */
    public function setModule($module);

    /**
     * @return ModuleActions
     */
    public function getModuleActions();

    /**
     * @param ModuleActions $moduleActions
     * @return ModuleActionsUsers
     */
    public function setModuleActions($moduleActions);
}
