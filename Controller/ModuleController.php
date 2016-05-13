<?php

namespace Bacon\Bundle\AclBundle\Controller;

use Symfony\Component\HttpFoundation\Request;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Method;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Template;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Security;
use Bacon\Bundle\CoreBundle\Controller\AdminController;
use Bacon\Bundle\AclBundle\Entity\Module;
use Bacon\Bundle\AclBundle\Form\Type\ModuleFormType;
use Bacon\Bundle\AclBundle\Form\Handler\ModuleFormHandler;

/**
 * Module controller.
 *
 * @Route("/module")
 */
class ModuleController extends AdminController
{

    /**
     * Lists all Module entities.
     *
     * @Route("/",defaults={"page"=1, "sort"="id", "direction"="asc"}, name="module")
     * @Route("/page/{page}/sort/{sort}/direction/{direction}/", defaults={"page"=1, "sort"="id", "direction"="asc"}, name="module_pagination")
     * @Method("GET")
     * @Security("has_role('ROLE_ADMIN')")
     * @Template()
     */
    public function indexAction($page, $sort, $direction)
    {
        $breadcumbs = $this->container->get('bacon_breadcrumbs');

        $breadcumbs->addItem([
            'title' => 'Module',
            'route' => '',
        ]);

        $breadcumbs->addItem([
            'title' => 'List',
            'route' => '',
        ]);

        $entity = new Module();

        if ($this->get('session')->has('module_search_session')) {
            $objSerialize = $this->get('session')->get('module_search_session');
            $entity = unserialize($objSerialize);
        }

        $query = $this->getDoctrine()->getRepository('BaconAclBundle:Module')->getQueryPagination($entity, $sort, $direction);

        $paginator = $this->getPagination($query, $page, Module::PER_PAGE);
        $paginator->setUsedRoute('module_pagination');

        $form = $this->createForm(ModuleFormType::class, $entity, [
            'search' => true,
        ]);

        return [
            'pagination'  => $paginator,
            'form_search' => $form->createView(),
            'form_delete' => $this->createDeleteForm()->createView(),
        ];
    }

   /**
    * Search filter Module entities.
    *
    * @Route("/search", name="module_search")
    * @Method({"POST","GET"})
    * @Security("has_role('ROLE_ADMIN')")
    * @Template()
    */
    public function searchAction(Request $request)
    {
        $this->get('session')->remove('module_search_session');

        if ($request->getMethod() === Request::METHOD_POST) {

            $form = $this->createForm(ModuleFormType::class, new Module(), [
            'search' => true,
            ]);

            $form->handleRequest($request);

            $this->get('session')->set('module_search_session', serialize($form->getData()));
        }

        return $this->redirect($this->generateUrl('module'));
    }

    /**
     * Displays a form to create a new Module entity.
     *
     * @Route("/new", name="module_new")
     * @Method({"GET", "POST"})
     * @Security("has_role('ROLE_ADMIN')")
     * @Template()
     */
    public function newAction(Request $request)
    {
        $breadcumbs = $this->container->get('bacon_breadcrumbs');

        $breadcumbs->addItem([
            'title' => 'Module',
            'route' => 'module',
        ]);

        $breadcumbs->addItem([
            'title' => 'New',
            'route' => '',
        ]);

        $form = $this->createForm(ModuleFormType::class, new Module());

        $form->handleRequest($request);

        if ($form->isSubmitted()) {
            $handler = new ModuleFormHandler(
                $form,
                $this->getDoctrine()->getManager(),
                $this->get('session')->getFlashBag()
            );

            if ($handler->save()) {
                return $this->redirect($this->generateUrl('module'));
            }
        }

        return [
            'form'   => $form->createView(),
        ];
    }

    /**
     * Displays a form to edit an existing Module entity.
     *
     * @Route("/{id}/edit", name="module_edit")
     * @Method({"GET", "POST"})
     * @Security("has_role('ROLE_ADMIN')")
     * @Template()
     */
    public function editAction(Request $request, Module $entity)
    {
        $breadcumbs = $this->container->get('bacon_breadcrumbs');

        $breadcumbs->addItem([
            'title' => 'Module',
            'route' => 'module',
        ]);

        $breadcumbs->addItem([
            'title' => 'Edit',
            'route' => '',
        ]);

        $form = $this->createForm(ModuleFormType::class, $entity);
        $deleteForm = $this->createDeleteForm('module_delete', $entity);
        $form->handleRequest($request);

        if ($form->isSubmitted()) {
            $handler = new ModuleFormHandler(
                $form,
                $this->getDoctrine()->getManager(),
                $this->get('session')->getFlashBag()
            );

            if ($entity = $handler->save()) {
                return $this->redirect($this->generateUrl('module'));
            }
        }

        return [
            'entity'      => $entity,
            'form'        => $form->createView(),
            'delete_form' => $deleteForm->createView(),
        ];
    }


    /**
     * Finds and displays a Module entity.
     *
     * @Route("/{id}", name="module_show")
     * @Method("GET")
     * @Security("has_role('ROLE_ADMIN')")
     * @Template()
     */
    public function showAction(Request $request, Module $entity)
    {
        $breadcumbs = $this->container->get('bacon_breadcrumbs');

        $breadcumbs->addItem([
            'title' => 'Module',
            'route' => 'module',
        ]);

        $breadcumbs->addItem([
            'title' => 'Details',
            'route' => '',
        ]);

        $deleteForm = $this->createDeleteForm('module_delete', $entity);

        return [
            'entity'      => $entity,
            'delete_form' => $deleteForm->createView(),
        ];
    }
    /**
     * Deletes a Module entity.
     *
     * @Route("/{id}", name="module_delete")
     * @Security("has_role('ROLE_ADMIN')")
     * @Method("DELETE")
     */
    public function deleteAction(Request $request, Module $entity)
    {
        $handler = new ModuleFormHandler(
            $this->createDeleteForm('module_delete', $entity),
            $this->get('doctrine')->getManager(),
            $this->get('session')->getFlashBag()
        );

        $handler->delete($entity);

        return $this->redirect($this->generateUrl('module'));
    }

}
