<?php

namespace Bacon\Bundle\AclBundle\Form\Handler;

use Bacon\Bundle\CoreBundle\Form\Handler\FormHandler;
use Symfony\Component\HttpFoundation\Request;

class ModuleActionsFormHandler extends FormHandler
{
    /**
     * @var Request
     */
    protected $request;

    /**
     * @return bool|mixed
     */
    public function save()
    {
        if ($this->getForm()->isValid()) {

            $data = $this->getForm()->getData();

            $created = is_null($data->getId()) ? true : false;
            try {
                if ($created) {
                    $dataRequest = $this->request->request->all();
                    $all = (bool) $dataRequest[$this->getForm()->getName()]['add_all_actions'];
                    if ($all) {
                        $actionsDefault = [
                            'INDEX' => 'Listagem de',
                            'NEW' => 'Cadastrar',
                            'EDIT' => 'Editar',
                            'SHOW' => 'Visualizar',
                            'DELETE' => 'Deletar',
                        ];

                        foreach ($actionsDefault as $identifier => $action) {
                            $entityCopy = clone $data;

                            $entityCopy->setName($action.' '.$data->getModule()->getName());
                            $entityCopy->setIdentifier($identifier);

                            $this->getEntityManager()->persist($entityCopy);
                            unset($entityCopy);
                        }
                    } else {
                        $this->getEntityManager()->persist($data);
                    }
                } else {
                    $this->getEntityManager()->merge($data);
                }

                $this->getEntityManager()->flush();

                $this->getFlashBag()->add('message', [
                    'type' => 'success',
                    'message' => sprintf('The record has been %s successfully.', $created ? 'created' : 'updated'),
                ]);

                return $data;

            } catch (\Exception $e) {

                $this->getFlashBag()->add('message', [
                    'type' => 'error',
                    'message' => $e->getMessage(),
                ]);

                return false;
            }
        }

        $errors = $this->getForm()->getErrors();

        foreach ($errors as $error) {
            $this->getFlashBag()->add('message', [
                'type' => 'error',
                'message' => $error->getMessage(),
            ]);
        }

        return false;
    }

    /**
     * @param Request $request
     */
    public function setRequest(Request $request)
    {
        $this->request = $request;
    }
}
