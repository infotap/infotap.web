<?php

namespace Infotap\AdminBundle\Controller;

use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;

use Infotap\AdminBundle\Entity\Department;
use Infotap\AdminBundle\Entity\UserPreference;
use Infotap\AdminBundle\Form\DepartmentType;

/**
 * Department controller.
 *
 */
class DepartmentController extends Controller
{

    /**
     * Lists all Department entities.
     *
     */
    public function indexAction()
    {
        $em = $this->getDoctrine()->getManager();

        $entities = $em->getRepository('InfotapAdminBundle:Department')->findAll();

        return $this->render('InfotapAdminBundle:Department:index.html.twig', array(
            'entities' => $entities,
        ));
    }

    public function apiDepartmentsAction(Request $request)
    {
        $response=new \stdClass;
        $response->success=true;
        $entities=array();
        $userdeptarr=array();
        $em = $this->getDoctrine()->getManager();

        $entities = $em->getRepository('InfotapAdminBundle:Department')->findAll();
        $token=$request->get('token');
        $userEntity = $em->getRepository('InfotapAdminBundle:User')->findOneBy(array('accessToken'=>$token));
        if($userEntity){
            $userPreferences = $em->getRepository('InfotapAdminBundle:UserPreference')->findBy(array('user'=>$userEntity));
            foreach ($userPreferences as $userPreference) {
                $userdeptarr[]=$userPreference->getDept()->getId();
            }
        }
        $departments=array();
        foreach($entities as $entity){
            $department=new \stdClass;
            $department->id=$entity->getId();
            $department->name=$entity->getName();
            $department->description=$entity->getDescription();
            $department->img=$entity->getImgUrl();
            $department->enabled=in_array($entity->getId(), $userdeptarr);
            $departments[]=$department;
        }
        $response->departments=$departments;
        $returnResponse=new Response(json_encode($response));
        $returnResponse->headers->set('Content-Type', 'application/json');
        $returnResponse->headers->set('Access-Control-Allow-Origin', '*');
        $returnResponse->headers->set("Access-Control-Allow-Headers", "Origin, X-Requested-With, Content-Type, Accept");
        $returnResponse->headers->set('Access-Control-Allow-Methods', 'GET,POST,PUT,DELETE,OPTIONS');
        return $returnResponse;
    }
    /**
     * Creates a new Department entity.
     *
     */
    public function createAction(Request $request)
    {
        $entity = new Department();
        $form = $this->createCreateForm($entity);
        $form->handleRequest($request);

        if ($form->isValid()) {
            $em = $this->getDoctrine()->getManager();
            $em->persist($entity);
            $em->flush();

            return $this->redirect($this->generateUrl('department_show', array('id' => $entity->getId())));
        }

        return $this->render('InfotapAdminBundle:Department:new.html.twig', array(
            'entity' => $entity,
            'form'   => $form->createView(),
        ));
    }

    /**
     * Creates a form to create a Department entity.
     *
     * @param Department $entity The entity
     *
     * @return \Symfony\Component\Form\Form The form
     */
    private function createCreateForm(Department $entity)
    {
        $form = $this->createForm(new DepartmentType(), $entity, array(
            'action' => $this->generateUrl('department_create'),
            'method' => 'POST',
        ));

        $form->add('submit', 'submit', array('label' => 'Create'));

        return $form;
    }

    /**
     * Displays a form to create a new Department entity.
     *
     */
    public function newAction()
    {
        $entity = new Department();
        $form   = $this->createCreateForm($entity);

        return $this->render('InfotapAdminBundle:Department:new.html.twig', array(
            'entity' => $entity,
            'form'   => $form->createView(),
        ));
    }

    /**
     * Finds and displays a Department entity.
     *
     */
    public function showAction($id)
    {
        $em = $this->getDoctrine()->getManager();

        $entity = $em->getRepository('InfotapAdminBundle:Department')->find($id);

        if (!$entity) {
            throw $this->createNotFoundException('Unable to find Department entity.');
        }

        $deleteForm = $this->createDeleteForm($id);

        return $this->render('InfotapAdminBundle:Department:show.html.twig', array(
            'entity'      => $entity,
            'delete_form' => $deleteForm->createView(),
        ));
    }

    /**
     * Displays a form to edit an existing Department entity.
     *
     */
    public function editAction($id)
    {
        $em = $this->getDoctrine()->getManager();

        $entity = $em->getRepository('InfotapAdminBundle:Department')->find($id);

        if (!$entity) {
            throw $this->createNotFoundException('Unable to find Department entity.');
        }

        $editForm = $this->createEditForm($entity);
        $deleteForm = $this->createDeleteForm($id);

        return $this->render('InfotapAdminBundle:Department:edit.html.twig', array(
            'entity'      => $entity,
            'edit_form'   => $editForm->createView(),
            'delete_form' => $deleteForm->createView(),
        ));
    }

    /**
    * Creates a form to edit a Department entity.
    *
    * @param Department $entity The entity
    *
    * @return \Symfony\Component\Form\Form The form
    */
    private function createEditForm(Department $entity)
    {
        $form = $this->createForm(new DepartmentType(), $entity, array(
            'action' => $this->generateUrl('department_update', array('id' => $entity->getId())),
            'method' => 'PUT',
        ));

        $form->add('submit', 'submit', array('label' => 'Update'));

        return $form;
    }
    /**
     * Edits an existing Department entity.
     *
     */
    public function updateAction(Request $request, $id)
    {
        $em = $this->getDoctrine()->getManager();

        $entity = $em->getRepository('InfotapAdminBundle:Department')->find($id);

        if (!$entity) {
            throw $this->createNotFoundException('Unable to find Department entity.');
        }

        $deleteForm = $this->createDeleteForm($id);
        $editForm = $this->createEditForm($entity);
        $editForm->handleRequest($request);

        if ($editForm->isValid()) {
            $em->flush();

            return $this->redirect($this->generateUrl('department_edit', array('id' => $id)));
        }

        return $this->render('InfotapAdminBundle:Department:edit.html.twig', array(
            'entity'      => $entity,
            'edit_form'   => $editForm->createView(),
            'delete_form' => $deleteForm->createView(),
        ));
    }
    /**
     * Deletes a Department entity.
     *
     */
    public function deleteAction(Request $request, $id)
    {
        $form = $this->createDeleteForm($id);
        $form->handleRequest($request);

        if ($form->isValid()) {
            $em = $this->getDoctrine()->getManager();
            $entity = $em->getRepository('InfotapAdminBundle:Department')->find($id);

            if (!$entity) {
                throw $this->createNotFoundException('Unable to find Department entity.');
            }

            $em->remove($entity);
            $em->flush();
        }

        return $this->redirect($this->generateUrl('department'));
    }

    /**
     * Creates a form to delete a Department entity by id.
     *
     * @param mixed $id The entity id
     *
     * @return \Symfony\Component\Form\Form The form
     */
    private function createDeleteForm($id)
    {
        return $this->createFormBuilder()
            ->setAction($this->generateUrl('department_delete', array('id' => $id)))
            ->setMethod('DELETE')
            ->add('submit', 'submit', array('label' => 'Delete'))
            ->getForm()
        ;
    }
}
