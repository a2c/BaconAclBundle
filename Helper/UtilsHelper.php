<?php

namespace Bacon\Bundle\AclBundle\Helper;
use FOS\UserBundle\Model\GroupInterface;

/**
 * Class CacheName
 * @package Bacon\Bundle\AclBundle\Helper
 * @author Adan Felipe Medeiros <adan.grg@gmail.com>
 * @version 1.0
 */
class UtilsHelper
{
    /**
     * @param string         $module
     * @param string         $action
     * @param GroupInterface $group
     *
     * @return string
     */
    public function generateNameForCache($module, $action, GroupInterface $group)
    {
        return md5($module.'_'.$action.'_'.strtolower($group->getName()));
    }
}