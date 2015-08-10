<?php

namespace AdminBundle\Controller\Slide;

use AppBundle\Entity\Slide;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;
use AdminBundle\Form\Slide\SlideType;

/**
 * @Route("/slide")
 */
class SlideController extends Controller
{
    /**
     * @Route("/index", name="admin_slide_index")
     */
    public function indexAction()
    {
        $em = $this->getDoctrine()->getManager();
        $entities = $em->getRepository("AppBundle:Slide")->findAll();
        
        return $this->render("admin/slide/index.html.twig", array(
            'entities' => $entities,
        ));
    }

    /**
     * @Route("/create", name="admin_slide_create")
     * @param Request $request
     * @return \Symfony\Component\HttpFoundation\RedirectResponse|\Symfony\Component\HttpFoundation\Response
     */
    public function createAction(Request $request)
    {
        $entity = new Slide();
        
        $languages = $this->container->getParameter("languages");
        
        $form = $this->createForm(new SlideType(array_keys($languages)), $entity);
        
        $form->handleRequest($request);
        
        if ($form->isValid()) {
            $em = $this->getDoctrine()->getManager();
            $em->persist($entity);
            $em->flush();
            
            $this->addFlash("success", "Slide was successfully created.");
            
            return $this->redirectToRoute("admin_slide_index");
        }
        
        return $this->render("admin/slide/create.html.twig", array(
            "form" => $form->createView(),
            "slide" => $entity,
        ));
    }

    /**
     * @Route("/edit/{id}", name="admin_slide_edit")
     * @param Request $request
     * @param $id
     * @return \Symfony\Component\HttpFoundation\RedirectResponse|\Symfony\Component\HttpFoundation\Response
     */
    public function editAction(Request $request, $id)
    {
        $em = $this->getDoctrine()->getManager();
        $entity = $em->getRepository("AppBundle:Slide")->find($id);
        
        if (!$entity) {
            throw $this->createNotFoundException("Unable to find slide.");
        }
        
        $languages = $this->container->getParameter("languages");
        
        $form = $this->createForm(new SlideType(array_keys($languages)), $entity);
        
        $form->handleRequest($request);
        
        if ($form->isValid()) {
            $em = $this->getDoctrine()->getManager();
            $em->persist($entity);
            $em->flush();
            
            $this->addFlash("success", "Slide was successfully updated.");
            
            return $this->redirectToRoute("admin_slide_edit", array("id" => $entity->getId()));
        }
        
        return $this->render("admin/slide/edit.html.twig", array(
            "form" => $form->createView(),
            "slide" => $entity,
        ));
    }

    /**
     * @Route("/delete/{id}", name="admin_slide_delete")
     * @param $id
     * @return \Symfony\Component\HttpFoundation\RedirectResponse
     */
    public function deleteAction($id)
    {
        $em = $this->getDoctrine()->getManager();
        $entity = $em->getRepository("AppBundle:Slide")->find($id);
        
        if (!$entity) {
            throw $this->createNotFoundException("Unable to find slide.");
        }
        
        $em = $this->getDoctrine()->getManager();
        $em->remove($entity);
        $em->flush();
        
        $this->addFlash("success", "Slide was successfully deleted.");

        return $this->redirectToRoute("admin_slide_index");
    }
}
