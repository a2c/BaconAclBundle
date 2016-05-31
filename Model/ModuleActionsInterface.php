<?php

namespace Bacon\Bundle\AclBundle\Model;

/**
 * Interface ModuleActionsInterface
 *
 * @package Bacon\Bundle\AclBundle\Model
 * @author Adan Felipe Medeiros <adan.grg@gmail.com>
 *
 * @version 1.0
 */
interface ModuleActionsInterface
{
    /**
     * @return string
     */
    public function getName();

    /**
     * @return string
     */
    public function getIdentifier();

    /**
     * @return int
     */
    public function getId();


    /**
     * @return ModuleInterface
     */
    public function getModule();
}
