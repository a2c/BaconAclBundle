BaconAcBundle
===============

[![Codacy Badge](https://api.codacy.com/project/badge/Grade/f982454ec80c40148a21f6ad2dfe0e3e)](https://www.codacy.com/app/adan-grg/BaconAclBundle?utm_source=github.com&amp;utm_medium=referral&amp;utm_content=a2c/BaconAclBundle&amp;utm_campaign=Badge_Grade)
[![Latest Stable Version](https://poser.pugx.org/baconmanager/acl-bundle/v/stable)](https://packagist.org/packages/baconmanager/acl-bundle) 
[![Total Downloads](https://poser.pugx.org/baconmanager/acl-bundle/downloads)](https://packagist.org/packages/baconmanager/acl-bundle) [![Latest Unstable Version](https://poser.pugx.org/baconmanager/acl-bundle/v/unstable)](https://packagist.org/packages/baconmanager/acl-bundle) 
[![License](https://poser.pugx.org/baconmanager/acl-bundle/license)](https://packagist.org/packages/baconmanager/acl-bundle)
[![SensioLabsInsight](https://insight.sensiolabs.com/projects/be83ee92-2fb5-49de-bc1a-991f0bbd09af/mini.png)](https://insight.sensiolabs.com/projects/be83ee92-2fb5-49de-bc1a-991f0bbd09af)

Este bundle é responsavel por adicionar e gerenciar o recurso de ACL do sistema.

## Instalação

Para instalar o bundle basta rodar o seguinte comando abaixo:

```bash
$ composer require baconmanager/acl-bundle
```
Agora adicione os seguintes bundles no arquivo AppKernel.php:

```php
<?php
// app/AppKernel.php
public function registerBundles()
{
    // ...
    new Bacon\Bundle\AclBundle\BaconAclBundle(),
    // ...
}
```
No arquivo **app/config/config.yml** adicione as seguintes configurações:

 * user_class: Entity do usuario
 * group_class: Entity do grupo de usuarios
 * route_redirect_after_save: Depois de salvar as alterações da ACL ele redireciona para a rota desse 	parâmentro
 * module_class: Entity dos modulos
 * module_actions: Entity das Ações dos Modulos
 * module_actions_group: Entity do relacionamento entre Modulo, Ações do Modulo e Grupo de usuario.

```yaml
bacon_acl:
    user_class: Bacon\Custom\UserBundle\Entity\User
    group_class: Bacon\Custom\UserBundle\Entity\Group
    route_redirect_after_save: fos_user_group_list
    configuration:
        entities:
            module_class: Bacon\Bundle\AclBundle\Entity\Module
            module_actions: AppBundle\Entity\ModuleActions
            module_actions_group: AppBundle\Entity\ModuleActionsGroup
```

Alterar a configuração do bundle FOSUserBundle

```yaml
fos_user_group:
    resource: "@FOSUserBundle/Resources/config/routing/group.xml"
    prefix: /admin/group
    
bacon_acl_module:
    resource: "@BaconAclBundle/Controller/"
    type:     annotation
    prefix:   /admin/    
```

## Registrando as Rotas

Adicionar no arquivo **app/config/routing.yml**

```yaml
fos_user:
    db_driver: orm 
    firewall_name: admin
    user_class: Bacon\Custom\UserBundle\Entity\User
    group:
        group_class: Bacon\Custom\UserBundle\Entity\Group
```


## Declarando as entities

Para que o bundle funcione corretamenta é necessario criar as entities Abaixo conforme configurado no arquivo **app/config/config.yml** 

```php
<?php
// src/Bacon/Custom/UserBundle/Entity/User.php

namespace Bacon\Custom\UserBundle\Entity;

use Bacon\Bundle\UserBundle\Entity\User as BaseUser;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass="Bacon\Custom\UserBundle\Repository\UserRepository")
 * @ORM\Table(name="auth_user")
 */
class User extends BaseUser
{
    /**
     * @ORM\ManyToMany(targetEntity="\Bacon\Custom\UserBundle\Entity\Group")
     * @ORM\JoinTable(name="auth_user_has_group",
     *      joinColumns={@ORM\JoinColumn(name="user_id", referencedColumnName="id")},
     *      inverseJoinColumns={@ORM\JoinColumn(name="group_id", referencedColumnName="id")}
     * )
     */
    protected $groups;

    public function __construct()
    {
        $this->groups = new ArrayCollection();
        parent::__construct();
    }

    /**
     * @return Group
     */
    public function getGroups()
    {
        return $this->groups;
    }

    /**
     * @param Group $groups
     * @return User
     */
    public function setGroups($groups)
    {
        $this->groups[] = $groups;
        return $this;
    }
}

```
```php
<?php
// src/Bacon/Custom/UserBundle/Entity/Group.php

namespace Bacon\Custom\UserBundle\Entity;

use FOS\UserBundle\Model\Group as BaseGroup;
use Doctrine\ORM\Mapping as ORM;
use Gedmo\Mapping\Annotation as Gedmo;

/**
 * Class Group
 * @package Bacon\Custom\UserBundle\Entity
 * @ORM\Entity()
 * @ORM\Table(name="auth_group")
 * @Gedmo\SoftDeleteable(fieldName="deletedAt", timeAware=false)
 */
class Group extends BaseGroup
{
    /**
     * @ORM\Column(type="integer")
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="AUTO")
     */
    protected $id;

    /**
     * @Gedmo\Timestampable(on="create")
     * @ORM\Column(name="created_at", type="datetime",nullable=false)
     */
    protected $createdAt;

    /**
     * @Gedmo\Timestampable(on="update")
     * @ORM\Column(name="updated_at", type="datetime",nullable=true)
     */
    protected $updatedAt;

    /**
     * @ORM\Column(name="deleted_at", type="datetime", nullable=true)
     */
    protected $deletedAt;

    /**
     * @return mixed
     */
    public function getId()
    {
        return $this->id;
    }

    /**
     * @param mixed $id
     * @return Group
     */
    public function setId($id)
    {
        $this->id = $id;
        return $this;
    }

    /**
     * @return mixed
     */
    public function getCreatedAt()
    {
        return $this->createdAt;
    }

    /**
     * @param mixed $createdAt
     * @return Group
     */
    public function setCreatedAt($createdAt)
    {
        $this->createdAt = $createdAt;
        return $this;
    }

    /**
     * @return mixed
     */
    public function getUpdatedAt()
    {
        return $this->updatedAt;
    }

    /**
     * @param mixed $updatedAt
     * @return Group
     */
    public function setUpdatedAt($updatedAt)
    {
        $this->updatedAt = $updatedAt;
        return $this;
    }

    /**
     * @return mixed
     */
    public function getDeletedAt()
    {
        return $this->deletedAt;
    }

    /**
     * @param mixed $deletedAt
     * @return Group
     */
    public function setDeletedAt($deletedAt)
    {
        $this->deletedAt = $deletedAt;
        return $this;
    }
}
```

```php
<?php
// src/AppBundle/Entity/ModuleActions.php

namespace AppBundle\Entity;

use Bacon\Bundle\AclBundle\Entity\ModuleActions as BaseModuleActions;
use Doctrine\ORM\Mapping as ORM;

/**
 * Class ModuleActions
 * @package AppBundle\Entity
 * @ORM\Table(name="module_actions")
 * @ORM\Entity(repositoryClass="AppBundle\Repository\ModuleActionsRepository")
 */
class ModuleActions extends BaseModuleActions
{
    /**
     * @ORM\ManyToOne(targetEntity="Bacon\Bundle\AclBundle\Entity\Module")
     * @ORM\JoinColumn(name="module_id", referencedColumnName="id" ,nullable=false)
     */
    private $module;

    /**
     * @return mixed
     */
    public function getModule()
    {
        return $this->module;
    }

    /**
     * @param mixed $module
     */
    public function setModule($module)
    {
        $this->module = $module;
        return $this;
    }
}

```

```php
<?php
// src/AppBundle/Entity/ModuleActionsGroup.php

use Bacon\Bundle\AclBundle\Model\ModuleActionsGroupInterface;
use Bacon\Bundle\CoreBundle\Entity\BaseEntity;
use Bacon\Custom\UserBundle\Entity\Group;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass="AppBundle\Repository\ModuleActionsGroupRepository")
 * @ORM\Table("module_actions_has_group")
 */
class ModuleActionsGroup extends BaseEntity implements ModuleActionsGroupInterface
{
    /**
     * @var boolean
     * @ORM\Column(name="enabled", type="boolean", options={"default" : 0}, nullable=false)
     */
    private $enabled;

    /**
     * @var Group
     *
     * @ORM\ManyToOne(targetEntity="\Bacon\Custom\UserBundle\Entity\Group")
     * @ORM\JoinColumn(name="group_id", referencedColumnName="id" ,nullable=false)
     */
    private $group;

    /**
     * @var \Bacon\Bundle\AclBundle\Entity\Module
     *
     * @ORM\ManyToOne(targetEntity="Bacon\Bundle\AclBundle\Entity\Module")
     * @ORM\JoinColumn(name="module_id", referencedColumnName="id", nullable=false)
     */
    private $module;

    /**
     * @var \AppBundle\Entity\ModuleActions
     *
     * @ORM\ManyToOne(targetEntity="AppBundle\Entity\ModuleActions")
     * @ORM\JoinColumn(name="module_actions_id", referencedColumnName="id" ,nullable=false)
     */
    private $moduleActions;

    /**
     * @return boolean
     */
    public function getEnabled()
    {
        return $this->enabled;
    }

    /**
     * @param boolean $enabled
     * @return ModuleActionsUsers
     */
    public function setEnabled($enabled)
    {
        $this->enabled = $enabled;
        return $this;
    }

    /**
     * @return Group
     */
    public function getGroup()
    {
        return $this->group;
    }

    /**
     * @param Group $group
     * @return ModuleActionsUsers
     */
    public function setGroup($group)
    {
        $this->group = $group;
        return $this;
    }

    /**
     * @return \Bacon\Bundle\AclBundle\Entity\Module
     */
    public function getModule()
    {
        return $this->module;
    }

    /**
     * @param \Bacon\Bundle\AclBundle\Entity\Module $module
     * @return ModuleActionsUsers
     */
    public function setModule($module)
    {
        $this->module = $module;
        return $this;
    }

    /**
     * @return ModuleActions
     */
    public function getModuleActions()
    {
        return $this->moduleActions;
    }

    /**
     * @param ModuleActions $moduleActions
     * @return ModuleActionsUsers
     */
    public function setModuleActions($moduleActions)
    {
        $this->moduleActions = $moduleActions;
        return $this;
    }
}
```
## Declarando os Repositories

Declare os repositories conforme configurado nas entities

```php
<?php
// src/AppBundle/Repository/ModuleActions.php

namespace AppBundle\Repository;

use Bacon\Bundle\AclBundle\Repository\ModuleActionsGetPagination;
use Bacon\Bundle\AclBundle\Repository\ModuleActionsRepositoryInterface;
use Doctrine\ORM\EntityRepository;

class ModuleActionsRepository extends EntityRepository implements ModuleActionsRepositoryInterface
{
    use ModuleActionsGetPagination;
}

```

```php
<?php
// src/AppBundle/Repository/ModuleActionsGroupRepository.php

use Bacon\Bundle\AclBundle\Repository\ModuleActionsGroupInterface as ModuleActionsGroupRepositoryInterface;
use Bacon\Bundle\AclBundle\Repository\ModuleActionsRepositoryInterface;
use Doctrine\ORM\EntityRepository;
use Bacon\Bundle\AclBundle\Repository\HasAuthorationRepository;
use FOS\UserBundle\Model\GroupInterface;

/**
 * Class ModuleActionsGroupRepository
 * @package AppBundle\Repository
 */
class ModuleActionsGroupRepository extends EntityRepository implements ModuleActionsGroupRepositoryInterface
{
    use HasAuthorationRepository;
}

```
