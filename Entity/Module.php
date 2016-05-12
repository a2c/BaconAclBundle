<?php

namespace Bacon\Bundle\AclBundle\Entity;

use Bacon\Bundle\AclBundle\Model\ModuleInterface;
use Bacon\Bundle\CoreBundle\Entity\BaseEntity;
use Doctrine\ORM\Mapping as ORM;

/**
 * Module
 *
 * @ORM\Entity(repositoryClass="Bacon\Bundle\AclBundle\Repository\ModuleRepository")
 * @ORM\Table("module")
 * @author Adan Felipe Medeiros <adan.grg@gmail.com>
 * @version
 */
class Module extends BaseEntity implements ModuleInterface
{
    /**
     * @var string
     *
     * @ORM\Column(name="name", type="string", length=80, nullable=false)
     */
    private $name;

    /**
     * @return string
     */
    public function getName()
    {
        return $this->name;
    }

    /**
     * @param string $name
     * @return Module
     */
    public function setName($name)
    {
        $this->name = $name;
        return $this;
    }

    public function __toString()
    {
        return $this->getName();
    }
}
