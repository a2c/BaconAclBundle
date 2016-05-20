<?php

namespace Bacon\Bundle\AclBundle\Twig\Extension;

use Bacon\Bundle\AclBundle\Entity\Module;
use FOS\UserBundle\Model\GroupInterface;
use Symfony\Component\DependencyInjection\ContainerInterface;

class AclExtension extends \Twig_Extension
{

    /**
     * @var ContainerInterface
     */
    private $container;

    public function __construct(ContainerInterface $container)
    {
        $this->container = $container;
    }

    /**
     * @return array
     */
    public function getFunctions()
    {
        return [
            new \Twig_SimpleFunction('bacon_acl_actions', [$this, 'getActionsToModule'], ['is_safe' => array('html')]),
            new \Twig_SimpleFunction('bacon_acl_authorization', [$this, 'validateViewHasAuthorization']),
        ];
    }

    /**
     * @param Module         $module
     * @param GroupInterface $group
     *
     * @return string
     */
    public function getActionsToModule(Module $module, GroupInterface $group)
    {
        $htmlReturn = '';

        $doctrine = $this->container->get('doctrine');

        $actions = $doctrine
            ->getRepository(
                $this->container->getParameter('bacon_acl.entities.module_actions')
            )->findBy(['module' => $module])
        ;

        $checkeds = $this->proccessHasChecked($group);

        foreach ($actions as $action) {

            $checked = '';

            if (in_array($action->getId(), $checkeds)) {
                $checked = 'checked';
            }

            $htmlReturn .= '
                <div class="checkbox">
                    <label style="padding-left: 18px">
                        <input type="checkbox" value="'. $action->getId() .'" name="actions_name['.$action->getId().']" '.$checked.' />
                        '. $action->getName() .'
                    </label>
                </div>
            ';
        }

        return $htmlReturn;
    }

    private function proccessHasChecked(GroupInterface $group)
    {
        $moduleActionsGroupNameClass    =   $this->container->getParameter('bacon_acl.entities.module_actions_group');

        $doctrine = $this->container->get('doctrine');

        $acls  = $doctrine
            ->getRepository($moduleActionsGroupNameClass)
            ->findBy(['group' => $group])
        ;

        $data = [];

        foreach ($acls as $acl) {
            $data[] = $acl->getModuleActions()->getId();
        }

        return $data;
    }

    /**
     * @param string $module
     * @param string $action
     */
    public function validateViewHasAuthorization($module, $action)
    {
        $user = $this->container->get('security.token_storage')->getToken()->getUser();
        $moduleActionsGroupNameClass    =   $this->container->getParameter('bacon_acl.entities.module_actions_group');

        foreach ($user->getGroups() as $group) {
            $return = $this
                ->getRepositoryModuleActionsGroup()
                ->hasAuthorization($module, $action, $group)
            ;

            if (!is_null($return)) {
                return true;
            }
        }

        return false;
    }

    /**
     * {@inheritdoc}
     */
    public function getName()
    {
        return 'bacon_acl_extension';
    }

    /**
     * @return \Doctrine\Common\Persistence\ObjectRepository
     */
    private function getRepositoryModuleActionsGroup()
    {
        $moduleActionsGroupNameClass    =   $this->container->getParameter('bacon_acl.entities.module_actions_group');

        $doctrine = $this->container->get('doctrine');

        return $doctrine->getRepository($moduleActionsGroupNameClass);
    }
}
