<?php

namespace Infotap\AdminBundle\Controller;

use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;

use Infotap\AdminBundle\Entity\UserPreference;
use Infotap\AdminBundle\Form\UserPreferenceType;

/**
 * UserPreference controller.
 *
 */
class UserPreferenceController extends Controller
{

    /**
     * Lists all UserPreference entities.
     *
     */
    public function indexAction()
    {
        $em = $this->getDoctrine()->getManager();

        $entities = $em->getRepository('InfotapAdminBundle:UserPreference')->findAll();

        return $this->render('InfotapAdminBundle:UserPreference:index.html.twig', array(
            'entities' => $entities,
        ));
    }

    public function apiPreferencesAction(Request $request)
    {
        $em = $this->getDoctrine()->getManager();
        if($this->get('request')->getMethod()=='OPTIONS'){
            $returnResponse=new Response(json_encode(array('accept'=>true)));
            $returnResponse->headers->set('Content-Type', 'application/json');
            $returnResponse->headers->set('Access-Control-Allow-Origin', '*');
            $returnResponse->headers->set("Access-Control-Allow-Headers", "Origin, X-Requested-With, Content-Type, Accept");
            $returnResponse->headers->set('Access-Control-Allow-Methods', 'GET,POST,PUT,DELETE,OPTIONS');
            return $returnResponse;
        }
        $return=array();
        $em = $this->getDoctrine()->getManager();
        $userEntity = $em->getRepository('InfotapAdminBundle:User')->findOneBy(array('accessToken'=>$request->query->get('token')));
        if($userEntity){
            $userPreferences = $em->getRepository('InfotapAdminBundle:UserPreference')->findBy(array('user'=>$userEntity));
            foreach ($userPreferences as $userPreference) {
                $userdeptarr[]=$userPreference->getDept()->getId();
                $em->remove($userPreference);
            }
            $em->flush();
            $userPreferencesStr=$request->query->get('departments');
            $userPreferences=explode(",",$userPreferencesStr);
            foreach ($userPreferences as $userPreferenceId) {
                $userDept=$em->getRepository('InfotapAdminBundle:Department')->find($userPreferenceId);
                $userPreference=new UserPreference();
                $userPreference->setDept($userDept);
                $userPreference->setUser($userEntity);
                $em->persist($userPreference);
            }
            $em->flush();
            $return['success']=true;
            $return['message']="Preferences saved successfully";
        }else{
            $return['success']=false;
            $return['message']="Something went wrong";
        }
        $returnResponse=new Response(json_encode($return));
        $returnResponse->headers->set('Content-Type', 'application/json');
        $returnResponse->headers->set('Access-Control-Allow-Origin', '*');
        $returnResponse->headers->set("Access-Control-Allow-Headers", "Origin, X-Requested-With, Content-Type, Accept");
        $returnResponse->headers->set('Access-Control-Allow-Methods', 'GET,POST,PUT,DELETE,OPTIONS');
        return $returnResponse;
    }
    /**
     * Creates a new UserPreference entity.
     *
     */
    public function createAction(Request $request)
    {
        $entity = new UserPreference();
        $form = $this->createCreateForm($entity);
        $form->handleRequest($request);

        if ($form->isValid()) {
            $em = $this->getDoctrine()->getManager();
            $em->persist($entity);
            $em->flush();

            return $this->redirect($this->generateUrl('userpreference_show', array('id' => $entity->getId())));
        }

        return $this->render('InfotapAdminBundle:UserPreference:new.html.twig', array(
            'entity' => $entity,
            'form'   => $form->createView(),
        ));
    }

    /**
     * Creates a form to create a UserPreference entity.
     *
     * @param UserPreference $entity The entity
     *
     * @return \Symfony\Component\Form\Form The form
     */
    private function createCreateForm(UserPreference $entity)
    {
        $form = $this->createForm(new UserPreferenceType(), $entity, array(
            'action' => $this->generateUrl('userpreference_create'),
            'method' => 'POST',
        ));

        $form->add('submit', 'submit', array('label' => 'Create'));

        return $form;
    }

    /**
     * Displays a form to create a new UserPreference entity.
     *
     */
    public function newAction()
    {
        $entity = new UserPreference();
        $form   = $this->createCreateForm($entity);

        return $this->render('InfotapAdminBundle:UserPreference:new.html.twig', array(
            'entity' => $entity,
            'form'   => $form->createView(),
        ));
    }

    /**
     * Finds and displays a UserPreference entity.
     *
     */
    public function showAction($id)
    {
        $em = $this->getDoctrine()->getManager();

        $entity = $em->getRepository('InfotapAdminBundle:UserPreference')->find($id);

        if (!$entity) {
            throw $this->createNotFoundException('Unable to find UserPreference entity.');
        }

        $deleteForm = $this->createDeleteForm($id);

        return $this->render('InfotapAdminBundle:UserPreference:show.html.twig', array(
            'entity'      => $entity,
            'delete_form' => $deleteForm->createView(),
        ));
    }

    /**
     * Displays a form to edit an existing UserPreference entity.
     *
     */
    public function editAction($id)
    {
        $em = $this->getDoctrine()->getManager();

        $entity = $em->getRepository('InfotapAdminBundle:UserPreference')->find($id);

        if (!$entity) {
            throw $this->createNotFoundException('Unable to find UserPreference entity.');
        }

        $editForm = $this->createEditForm($entity);
        $deleteForm = $this->createDeleteForm($id);

        return $this->render('InfotapAdminBundle:UserPreference:edit.html.twig', array(
            'entity'      => $entity,
            'edit_form'   => $editForm->createView(),
            'delete_form' => $deleteForm->createView(),
        ));
    }

    /**
    * Creates a form to edit a UserPreference entity.
    *
    * @param UserPreference $entity The entity
    *
    * @return \Symfony\Component\Form\Form The form
    */
    private function createEditForm(UserPreference $entity)
    {
        $form = $this->createForm(new UserPreferenceType(), $entity, array(
            'action' => $this->generateUrl('userpreference_update', array('id' => $entity->getId())),
            'method' => 'PUT',
        ));

        $form->add('submit', 'submit', array('label' => 'Update'));

        return $form;
    }
    /**
     * Edits an existing UserPreference entity.
     *
     */
    public function updateAction(Request $request, $id)
    {
        $em = $this->getDoctrine()->getManager();

        $entity = $em->getRepository('InfotapAdminBundle:UserPreference')->find($id);

        if (!$entity) {
            throw $this->createNotFoundException('Unable to find UserPreference entity.');
        }

        $deleteForm = $this->createDeleteForm($id);
        $editForm = $this->createEditForm($entity);
        $editForm->handleRequest($request);

        if ($editForm->isValid()) {
            $em->flush();

            return $this->redirect($this->generateUrl('userpreference_edit', array('id' => $id)));
        }

        return $this->render('InfotapAdminBundle:UserPreference:edit.html.twig', array(
            'entity'      => $entity,
            'edit_form'   => $editForm->createView(),
            'delete_form' => $deleteForm->createView(),
        ));
    }
    /**
     * Deletes a UserPreference entity.
     *
     */
    public function deleteAction(Request $request, $id)
    {
        $form = $this->createDeleteForm($id);
        $form->handleRequest($request);

        if ($form->isValid()) {
            $em = $this->getDoctrine()->getManager();
            $entity = $em->getRepository('InfotapAdminBundle:UserPreference')->find($id);

            if (!$entity) {
                throw $this->createNotFoundException('Unable to find UserPreference entity.');
            }

            $em->remove($entity);
            $em->flush();
        }

        return $this->redirect($this->generateUrl('userpreference'));
    }

    /**
     * Creates a form to delete a UserPreference entity by id.
     *
     * @param mixed $id The entity id
     *
     * @return \Symfony\Component\Form\Form The form
     */
    private function createDeleteForm($id)
    {
        return $this->createFormBuilder()
            ->setAction($this->generateUrl('userpreference_delete', array('id' => $id)))
            ->setMethod('DELETE')
            ->add('submit', 'submit', array('label' => 'Delete'))
            ->getForm()
        ;
    }
}
