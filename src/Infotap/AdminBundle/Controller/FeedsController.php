<?php

namespace Infotap\AdminBundle\Controller;

use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;

use Infotap\AdminBundle\Entity\Feeds;
use Infotap\AdminBundle\Form\FeedsType;
use Knp\Component\Pager\Paginator;

/**
 * Feeds controller.
 *
 */
class FeedsController extends Controller
{

    /**
     * Lists all Feeds entities.
     *
     */
    public function indexAction()
    {
        $em = $this->getDoctrine()->getManager();

        $entities = $em->getRepository('InfotapAdminBundle:Feeds')->findAll();

        return $this->render('InfotapAdminBundle:Feeds:index.html.twig', array(
            'entities' => $entities,
        ));
    }
    private function getPaginationByQuery($query,$page,$count=10){
            $paginator = new Paginator();
            $paginator  = $this->get('knp_paginator');
            $entities = $paginator->paginate(
                $query,
                $page,
                $count
            );
            return $entities;
    }
    public function apiFeedsAction(Request $request)
    {
        $response=new \stdClass;
        $response->success=true;
        $entities=array();
        $em = $this->getDoctrine()->getManager();
        $token=$request->get('token');
        $department=$request->get('department');
        $userEntity = $em->getRepository('InfotapAdminBundle:User')->findOneBy(array('accessToken'=>$token));
        if($userEntity){
            $UserPreferences = $em->getRepository('InfotapAdminBundle:UserPreference')->findBy(array('user'=>$userEntity));
            $deptIds=array();
            foreach ($UserPreferences as $UserPreference) {
                $deptIds[]=$UserPreference->getDept()->getId();
            }
            $qb = $em->createQueryBuilder() 
                ->select('f,d')
                ->from('InfotapAdminBundle:Feeds', 'f')
                ->join('f.dept', 'd');
            $qb->andWhere("(f.gender is null) or (f.gender is not null and f.gender='".$userEntity->getGender()."')");
            $qb->andWhere("(f.city is null) or (f.city is not null and f.city='".$userEntity->getCity()."')");
            $qb->andWhere("(f.area is null) or (f.area is not null and f.area='".$userEntity->getArea()."')");
            $qb->andWhere("(f.aadharId is null) or (f.aadharId is not null and f.aadharId='".$userEntity->getAadharId()."')");
            if($department){
                $departmentEntity = $em->getRepository('InfotapAdminBundle:Department')->find($department);
                if($departmentEntity){
                    $qb->andWhere("d=".$departmentEntity->getId());
                }
            }else{
                $deptidsstr=implode(",",$deptIds);
                $qb->andWhere('d.id in ('.($deptidsstr?$deptidsstr:'0').')');
                $qb->addOrderBy('f.creationTime','DESC');
            }
            //$qb->andWhere("(f.ageFrom is null) or (f.ageFrom is not null and f.ageFrom='".$userEntity->getAadharId()."')");
            //$qb->andWhere("(f.ageTo is null) or (f.ageTo is not null and f.ageTo='".$userEntity->getAadharId()."')");
            $qbQuery=$qb->getQuery();
            $paginationPage=$this->get('request')->query->get('page', 1);//$paginationPage=$paginationPage?$paginationPage:1;
            $paginationCount=$this->get('request')->query->get('count', 10);//$paginationCount=$paginationCount?$paginationCount:10;
            $entities = $this->getPaginationByQuery($qbQuery,$paginationPage,$paginationCount);
        }
        $feeds=array();
        foreach($entities as $entity){
            $feed=new \stdClass;
            $feed->id=$entity->getId();
            $feed->title=$entity->getTitle();
            $feed->message=$entity->getMessage();
            $feed->creation_time=$entity->getCreationTime()->format('d-m-Y');
            $department=new \stdClass;
            $department->id=$entity->getDept()->getId();
            $department->name=$entity->getDept()->getName();
            $department->img=$entity->getDept()->getImgUrl();
            $department->description=$entity->getDept()->getDescription();
            $feed->department=$department;
            $feeds[]=$feed;
        }
        $response->feeds=$feeds;
        if($userEntity){
            $response->total_count=$entities->getTotalItemCount();
            $response->current_page=$response->total_count?$paginationPage:0;
            $response->total_pages=ceil($entities->getTotalItemCount()/$paginationCount);
        }else{
            $response->total_count=0;
            $response->current_page=0;
            $response->total_pages=0;
        }
        $returnResponse=new Response(json_encode($response));
        $returnResponse->headers->set('Content-Type', 'application/json');
        $returnResponse->headers->set('Access-Control-Allow-Origin', '*');
        $returnResponse->headers->set("Access-Control-Allow-Headers", "Origin, X-Requested-With, Content-Type, Accept");
        $returnResponse->headers->set('Access-Control-Allow-Methods', 'GET,POST,PUT,DELETE,OPTIONS');
        return $returnResponse;
    }

    public function apiFeedGetAction(Request $request, $id)
    {
        $response=new \stdClass;
        $response->success=true;
        $entities=array();
        $em = $this->getDoctrine()->getManager();
        $token=$request->get('token');
        $department=$request->get('department');
        $userEntity = $em->getRepository('InfotapAdminBundle:User')->findOneBy(array('accessToken'=>$token));
        // if($userEntity){
            $entity = $em->getRepository('InfotapAdminBundle:Feeds')->findOneBy(array('id'=>$id));
            $feed=new \stdClass;
            $feed->id=$entity->getId();
            $feed->title=$entity->getTitle();
            $feed->message=$entity->getMessage();
            $feed->creation_time=$entity->getCreationTime()->format('d-m-Y');
            $department=new \stdClass;
            $department->id=$entity->getDept()->getId();
            $department->name=$entity->getDept()->getName();
            $department->img=$entity->getDept()->getImgUrl();
            $department->description=$entity->getDept()->getDescription();
            $feed->department=$department;
        // }
        $response->feed=$feed;

        $returnResponse=new Response(json_encode($response));
        $returnResponse->headers->set('Content-Type', 'application/json');
        $returnResponse->headers->set('Access-Control-Allow-Origin', '*');
        $returnResponse->headers->set("Access-Control-Allow-Headers", "Origin, X-Requested-With, Content-Type, Accept");
        $returnResponse->headers->set('Access-Control-Allow-Methods', 'GET,POST,PUT,DELETE,OPTIONS');

        return $returnResponse;
    }
    /**
     * Creates a new Feeds entity.
     *
     */
    public function createAction(Request $request)
    {
        $entity = new Feeds();
        $form = $this->createCreateForm($entity);
        $form->handleRequest($request);

        if ($form->isValid()) {
            $em = $this->getDoctrine()->getManager();
            
            $em->persist($entity);
            
            $em->flush();




            $qb = $em->createQueryBuilder() 
                ->select('u')
                ->from('InfotapAdminBundle:User', 'u');


            $query_str="select distinct u.androidRegId from InfotapAdminBundle:User u ";
            //$query_str.=" LEFT JOIN u.dept d";
            $query_str.=" where u.androidRegId is not null";

            /*if($entity->getAadharId()){
                $query_str.=" and  (( u.aadharId = '".$entity->getAadharId().") or ( 1=2 '";
            }else {
                $query_str.=" and (( 1=1 ";
            }*/
            if($entity->getAadharId()){
                $query_str.=" and  u.aadharId = '".$entity->getAadharId()."'";
            }
            /*if($entity->getLocation()){
                $query_str.=" and  u.location = '".$entity->getLocation()."'";
            }*/
            if($entity->getCity()){
                $query_str.=" and  u.city = '".$entity->getCity()."'";
            }
            if($entity->getArea()){
                $query_str.=" and  u.area = '".$entity->getArea()."'";
            }
            if($entity->getState()){
                $query_str.=" and  u.state = '".$entity->getState()."'";
            }
            if($entity->getPincode()){
                $query_str.=" and  u.pincode = '".$entity->getPincode()."'";
            }
            if(!is_null($entity->getGender())){
                $query_str.=" and  u.gender = '".$entity->getGender()."'";
            }
            if($entity->getAgeFrom()){
                $query_str.=" and u.dob is not null and (DATE_DIFF(CURRENT_DATE(),u.dob) / 365.25) >".($entity->getAgeFrom());
            }
            if($entity->getAgeTo()){
                $query_str.=" and u.dob is not null and (DATE_DIFF(CURRENT_DATE(),u.dob) / 365.25) <".($entity->getAgeTo());
            }
            /*$UserPreferences = $em->getRepository('InfotapAdminBundle:UserPreference')->findBy(array('user'=>$userEntity));
            $deptIds=array();
            foreach ($UserPreferences as $UserPreference) {
                $deptIds[]=$UserPreference->getDept()->getId();
            }
            $deptidsstr=implode(",",$deptIds);
            $query_str.="d.id in (".($deptidsstr?$deptidsstr:'0').")";*/
            //$query_str.=" )) ";
            //print_r($query_str);
            $query = $em->createQuery($query_str);


            //$qb->andWhere("(f.gender is null) or (f.gender is not null and f.gender='".$userEntity->getGender()."')");
            //$qb->andWhere("(f.city is null) or (f.city is not null and f.city='".$userEntity->getCity()."')");
            //$qb->andWhere("(f.area is null) or (f.area is not null and f.area='".$userEntity->getArea()."')");
            //$qb->andWhere("(f.aadharId is null) or (f.aadharId is not null and f.aadharId='".$userEntity->getAadharId()."')");
            // {
            //     $deptidsstr=implode(",",$deptIds);
            //$qb->andWhere('u.id in ('.($deptidsstr?$deptidsstr:'0').')');
            //     $qb->addOrderBy('f.creationTime','DESC');
            // }
            $registrationIDs=array();
            $entities=$query->getResult();
            foreach($entities as $userEntity) {
                if($userEntity['androidRegId']){
                    $registrationIDs[]=$userEntity['androidRegId'];
                }
            }

            //print_r($registrationIDs);

            if(sizeof($registrationIDs)){
                $apiKey = "AIzaSyCNg4byDJdF_uReKH1gEryjmuYLkkXhBRU";
                //$registrationIDs = array("APA91bF_B_Fip3Uc8BrKqrNYnGaxvYZu-FxiiX3NkI0f8PUUOwJMsAkPOt1bjyGFDaaOVgE_EkbiJLMaXJ2iSgOEhGrTH796GvWyuK1RpC-JeDw9V8TZg8XSVpbo57xmsuRKGAejDyp7tDlFEhNiuqw-sNrqLYqN2g");
                $message = $entity->getTitle();
                $url = 'https://android.googleapis.com/gcm/send';
                $tickerText='';
                $contentTitle='New Alert';
                $contentText='';
                $fields = array(
                        'registration_ids'  => $registrationIDs,
                        'data'              =>  array('message' => $message,'source'=>'INFOTAP','button'=>'View Message','type' => 'NEW_FEED','feed_id'=>'4', 'tickerText' => $tickerText, 'contentTitle' => $contentTitle,'title' => 'InfoTap - New Alert', "contentText" => $contentText),
                        );
                $headers = array( 
                        'Authorization: key=' . $apiKey,
                        'Content-Type: application/json'
                        );
                $ch = curl_init();
                curl_setopt( $ch, CURLOPT_URL, $url );
                curl_setopt( $ch, CURLOPT_POST, true );
                curl_setopt( $ch, CURLOPT_HTTPHEADER, $headers);
                curl_setopt( $ch, CURLOPT_RETURNTRANSFER, true );
                curl_setopt ($ch, CURLOPT_SSL_VERIFYHOST, 0);
                curl_setopt ($ch, CURLOPT_SSL_VERIFYPEER, 0); 
                curl_setopt( $ch, CURLOPT_POSTFIELDS, json_encode($fields) );
                curl_setopt($ch, CURLOPT_IPRESOLVE, CURL_IPRESOLVE_V4 );
                
                $result = curl_exec($ch);
                if(curl_errno($ch)){ echo 'Curl error: ' . curl_error($ch); }
                curl_close($ch);
                
            }




            //return $this->redirect($this->generateUrl('feeds_show', array('id' => $entity->getId())));
        }

        return $this->render('InfotapAdminBundle:Feeds:new.html.twig', array(
            'entity' => $entity,
            'form'   => $form->createView(),
        ));
    }

    /**
     * Creates a form to create a Feeds entity.
     *
     * @param Feeds $entity The entity
     *
     * @return \Symfony\Component\Form\Form The form
     */
    private function createCreateForm(Feeds $entity)
    {
        $form = $this->createForm(new FeedsType(), $entity, array(
            'action' => $this->generateUrl('feeds_create'),
            'method' => 'POST',
        ));

        $form->add('submit', 'submit', array('label' => 'Post Now','attr'=>array('class'=>'btn btn-lg btn-primary')));

        return $form;
    }

    /**
     * Displays a form to create a new Feeds entity.
     *
     */
    public function newAction()
    {
        $entity = new Feeds();
        $form   = $this->createCreateForm($entity);

        return $this->render('InfotapAdminBundle:Feeds:new.html.twig', array(
            'entity' => $entity,
            'form'   => $form->createView(),
        ));
    }

    /**
     * Finds and displays a Feeds entity.
     *
     */
    public function showAction($id)
    {
        $em = $this->getDoctrine()->getManager();

        $entity = $em->getRepository('InfotapAdminBundle:Feeds')->find($id);

        if (!$entity) {
            throw $this->createNotFoundException('Unable to find Feeds entity.');
        }

        $deleteForm = $this->createDeleteForm($id);

        return $this->render('InfotapAdminBundle:Feeds:show.html.twig', array(
            'entity'      => $entity,
            'delete_form' => $deleteForm->createView(),
        ));
    }

    /**
     * Displays a form to edit an existing Feeds entity.
     *
     */
    public function editAction($id)
    {
        $em = $this->getDoctrine()->getManager();

        $entity = $em->getRepository('InfotapAdminBundle:Feeds')->find($id);

        if (!$entity) {
            throw $this->createNotFoundException('Unable to find Feeds entity.');
        }

        $editForm = $this->createEditForm($entity);
        $deleteForm = $this->createDeleteForm($id);

        return $this->render('InfotapAdminBundle:Feeds:edit.html.twig', array(
            'entity'      => $entity,
            'edit_form'   => $editForm->createView(),
            'delete_form' => $deleteForm->createView(),
        ));
    }

    /**
    * Creates a form to edit a Feeds entity.
    *
    * @param Feeds $entity The entity
    *
    * @return \Symfony\Component\Form\Form The form
    */
    private function createEditForm(Feeds $entity)
    {
        $form = $this->createForm(new FeedsType(), $entity, array(
            'action' => $this->generateUrl('feeds_update', array('id' => $entity->getId())),
            'method' => 'PUT',
        ));

        $form->add('submit', 'submit', array('label' => 'Update'));

        return $form;
    }
    /**
     * Edits an existing Feeds entity.
     *
     */
    public function updateAction(Request $request, $id)
    {
        $em = $this->getDoctrine()->getManager();

        $entity = $em->getRepository('InfotapAdminBundle:Feeds')->find($id);

        if (!$entity) {
            throw $this->createNotFoundException('Unable to find Feeds entity.');
        }

        $deleteForm = $this->createDeleteForm($id);
        $editForm = $this->createEditForm($entity);
        $editForm->handleRequest($request);

        if ($editForm->isValid()) {
            $em->flush();

            return $this->redirect($this->generateUrl('feeds_edit', array('id' => $id)));
        }

        return $this->render('InfotapAdminBundle:Feeds:edit.html.twig', array(
            'entity'      => $entity,
            'edit_form'   => $editForm->createView(),
            'delete_form' => $deleteForm->createView(),
        ));
    }
    /**
     * Deletes a Feeds entity.
     *
     */
    public function deleteAction(Request $request, $id)
    {
        $form = $this->createDeleteForm($id);
        $form->handleRequest($request);

        if ($form->isValid()) {
            $em = $this->getDoctrine()->getManager();
            $entity = $em->getRepository('InfotapAdminBundle:Feeds')->find($id);

            if (!$entity) {
                throw $this->createNotFoundException('Unable to find Feeds entity.');
            }

            $em->remove($entity);
            $em->flush();
        }

        return $this->redirect($this->generateUrl('feeds'));
    }

    /**
     * Creates a form to delete a Feeds entity by id.
     *
     * @param mixed $id The entity id
     *
     * @return \Symfony\Component\Form\Form The form
     */
    private function createDeleteForm($id)
    {
        return $this->createFormBuilder()
            ->setAction($this->generateUrl('feeds_delete', array('id' => $id)))
            ->setMethod('DELETE')
            ->add('submit', 'submit', array('label' => 'Delete'))
            ->getForm()
        ;
    }
}
