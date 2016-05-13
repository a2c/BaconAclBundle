<?php

namespace Bacon\Bundle\AclBundle\Model;

/**
 * Interface ModuleInterface
 * @package Bacon\Bundle\AclBundle\Model
 * @author Adan Felipe Medeiros <adan.grg@gmail.com>
 * @version 1.0
 */
interface ModuleInterface
{
    /**
     * @param string $name
     */
    public function setName($name);

    /**
     * @return string
     */
    public function getName();
}
