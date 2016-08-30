<?php

namespace Bacon\Bundle\AclBundle\Service;

use Doctrine\Bundle\DoctrineBundle\Registry;
use FOS\UserBundle\Model\UserInterface;
use Symfony\Component\Security\Core\Authentication\Token\Storage\TokenStorage;

/**
 * Class AuthorizationService
 * @package Bacon\Bundle\AclBundle\Service
 * @author Adan Felipe Medeiros <adan.grg@gmail.com>
 * @version 1.0
 */
class AuthorizationService
{
    /**
     * @var TokenStorage
     */
    private $tokenStorage;

    /**
     * @var Registry
     */
    private $doctrine;

    /**
     * @var UserInterface|null
     */
    private $user = null;

    /**
     * @var string
     */
    private $moduleActionsGroup;

    /**
     * AuthorizationService constructor.
     *
     * @param TokenStorage $tokenStorage
     */
    public function __construct(TokenStorage $tokenStorage)
    {
        $this->tokenStorage = $tokenStorage;
    }

    /**
     * @param $moduleActionsGroup
     * @return $this
     */
    public function setModuleActionsGroup($moduleActionsGroup)
    {
        $this->moduleActionsGroup = $moduleActionsGroup;
        return $this;
    }

    /**
     * @param Registry $doctrine
     */
    public function setDoctrine(Registry $doctrine)
    {
        $this->doctrine = $doctrine;
    }

    /**
     * @return Registry
     */
    protected function getDoctrine()
    {
        return $this->doctrine;
    }

    /**
     * @param UserInterface $user
     * @return $this
     */
    public function setUser(UserInterface $user)
    {
        $this->user = $user;
        return $this;
    }

    /**
     * @return UserInterface|null
     */
    public function getUser()
    {
        if (is_null($this->user)) {
            $this->user = $this->tokenStorage->getToken()->getUser();
        }

        return $this->user;
    }

    /**
     * @
     */
    public function authorize($module, $action)
    {
        $groups =   $this->getUser()->getGroups();

        foreach ($groups as $group) {
            if (!is_null($this->getDoctrine()->getRepository($this->moduleActionsGroup)->hasAuthorization($module, $action, $group)))
                return true;
        }

        return false;
    }
}
