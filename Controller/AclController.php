<?php

namespace Bacon\Bundle\AclBundle\Controller;

use Bacon\Bundle\CoreBundle\Controller\AdminController;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Method;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Template;
use Symfony\Component\HttpFoundation\Request;

/**
 *
 * Class AclController
 * @package Bacon\Bundle\AclBundle\Controller
 * @author Adan Felipe Medeiros <adan.grg@gmail.com>
 * @version 1.0
 * @Route("/acl")
 */
class AclController extends AdminController
{
    /**
     * @param Request $request
     *
     * @return array
     * @Route("/{groupName}", name="acl_index")
     * @Method("GET")
     * @Template()
     */
    public function indexAction($groupName)
    {
        $acl = $this->get('bacon_acl.service.authorization');

        if (!$acl->authorize('acl', 'ACL')) {
            throw $this->createAccessDeniedException();
        }

        $groupClassName     =   $this->getParameter('bacon_acl.group_class');
        $moduleClassName    =   $this->getParameter('bacon_acl.entities.module_class');

        $module = $this->getDoctrine()->getRepository($moduleClassName)->findAll();
        $group  = $this->getDoctrine()->getRepository($groupClassName)->findOneBy(array('name' => urldecode($groupName)));

        return [
            'modules' => $module,
            'group'  => $group
        ];
    }

    /**
     * @param Request $request
     * @return array
     * @Route("/save", name="acl_post")
     * @Method("POST")
     * @Template()
     */
    public function postAclAction(Request $request)
    {
        $acl = $this->get('bacon_acl.service.authorization');

        if (!$acl->authorize('acl', 'ACL')) {
            throw $this->createAccessDeniedException();
        }

        $data = $request->request->all();

        $groupNameClass                 =   $this->getParameter('bacon_acl.group_class');
        $routeRedirect                  =   $this->getParameter('bacon_acl.route_redirect_after_save');
        $moduleActionsNameClass         =   $this->getParameter('bacon_acl.entities.module_actions');
        $moduleActionsGroupNameClass    =   $this->getParameter('bacon_acl.entities.module_actions_group');

        if (isset($data['group_id'])) {
            $group = $this->getDoctrine()->getRepository($groupNameClass)->find($data['group_id']);

            $acls  = $this
                ->getDoctrine()
                ->getRepository($moduleActionsGroupNameClass)
                ->findBy(['group' => $group])
            ;

            if (!empty($acls)) {
                foreach ($acls as $acl) {
                    $this->getDoctrine()->getManager()->remove($acl);
                }

                $this->getDoctrine()->getManager()->flush();
            }


            if (isset($data['actions_name'])) {
                $actionsNames = $data['actions_name'];

                foreach ($actionsNames as $actionsName) {

                    $actionTemp = $this->getDoctrine()->getRepository($moduleActionsNameClass)->find($actionsName);

                    $objSave = new $moduleActionsGroupNameClass();

                    $objSave->setGroup($group);
                    $objSave->setModule($actionTemp->getModule());
                    $objSave->setModuleActions($actionTemp);
                    $objSave->setEnabled(true);

                    $this->getDoctrine()->getManager()->persist($objSave);

                    unset($objSave);
                    unset($actionTemp);
                }

                $this->getDoctrine()->getManager()->flush();
            }
        }
        
        $this->getFlashBag()->add('message', [
            'type' => 'success',
            'message' => 'The acl has been updated successfully',
        ]);

        return $this->redirectToRoute($routeRedirect);
    }
}
