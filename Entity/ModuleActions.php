<?php

namespace Bacon\Bundle\AclBundle\Entity;

use Bacon\Bundle\AclBundle\Model\ModuleActionsInterface;
use Bacon\Bundle\CoreBundle\Entity\BaseEntity;
use Doctrine\ORM\Mapping as ORM;

/**
 * ModuleActions
 *
 * @ORM\MappedSuperclass()
 * @author Adan Felipe Medeiros <adan.grg@gmail.com>
 * @version 1.0
 *
 */
abstract class ModuleActions extends BaseEntity implements ModuleActionsInterface
{
    /**
     * @var string
     *
     * @ORM\Column(name="name", type="string", length=80, nullable=false)
     */
    protected $name;

    /**
     * @var string
     *
     * @ORM\Column(name="identifier", type="string", length=45, nullable=false)
     */
    protected $identifier;


    /**
     * @return string
     */
    public function getName()
    {
        return $this->name;
    }

    /**
     * @param string $name
     * @return ModuleActions
     */
    public function setName($name)
    {
        $this->name = $name;
        return $this;
    }

    /**
     * @return string
     */
    public function getIdentifier()
    {
        return $this->identifier;
    }

    /**
     * @param string $identifier
     * @return ModuleActions
     */
    public function setIdentifier($identifier)
    {
        $this->identifier = $identifier;
        return $this;
    }
}

