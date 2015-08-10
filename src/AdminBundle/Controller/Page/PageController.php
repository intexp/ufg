<?php

namespace AdminBundle\Controller\Page;

use AppBundle\Entity\Page;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;
use AdminBundle\Form\Page\PageType;

/**
 * @Route("/page")
 */
class PageController extends Controller
{
    /**
     * @Route("/index", name="admin_page_index")
     */
    public function indexAction()
    {
        $em = $this->getDoctrine()->getManager();
        $entities = $em->getRepository("AppBundle:Page")->findAll();
        
        return $this->render("admin/page/index.html.twig", array(
            'entities' => $entities,
        ));
    }

    /**
     * @Route("/create", name="admin_page_create")
     * @param Request $request
     * @return \Symfony\Component\HttpFoundation\RedirectResponse|\Symfony\Component\HttpFoundation\Response
     */
    public function createAction(Request $request)
    {
        $entity = new Page();
        
        $languages = $this->container->getParameter("languages");
        
        $form = $this->createForm(new PageType(array_keys($languages)), $entity);
        
        $form->handleRequest($request);
        
        if ($form->isValid()) {
            $em = $this->getDoctrine()->getManager();
            $em->persist($entity);
            $em->flush();
            
            $this->addFlash("success", "Page was successfully created.");
            
            return $this->redirectToRoute("admin_page_index");
        }
        
        return $this->render("admin/page/create.html.twig", array(
            "form" => $form->createView(),
            "page" => $entity,
        ));
    }

    /**
     * @Route("/edit/{id}", name="admin_page_edit")
     * @param Request $request
     * @param $id
     * @return \Symfony\Component\HttpFoundation\RedirectResponse|\Symfony\Component\HttpFoundation\Response
     */
    public function editAction(Request $request, $id)
    {
        $em = $this->getDoctrine()->getManager();
        $entity = $em->getRepository("AppBundle:Page")->find($id);
        
        if (!$entity) {
            throw $this->createNotFoundException("Unable to find page.");
        }
        
        $languages = $this->container->getParameter("languages");
        
        $form = $this->createForm(new PageType(array_keys($languages)), $entity);
        
        $form->handleRequest($request);
        
        if ($form->isValid()) {
            $em = $this->getDoctrine()->getManager();
            $em->persist($entity);
            $em->flush();
            
            $this->addFlash("success", "Page was successfully updated.");
            
            return $this->redirectToRoute("admin_page_edit", array("id" => $entity->getId()));
        }
        
        return $this->render("admin/page/edit.html.twig", array(
            "form" => $form->createView(),
            "page" => $entity,
        ));
    }

    /**
     * @Route("/delete/{id}", name="admin_page_delete")
     * @param $id
     * @return \Symfony\Component\HttpFoundation\RedirectResponse
     */
    public function deleteAction($id)
    {
        $em = $this->getDoctrine()->getManager();
        $entity = $em->getRepository("AppBundle:Page")->find($id);
        
        if (!$entity) {
            throw $this->createNotFoundException("Unable to find page.");
        }
        
        $em = $this->getDoctrine()->getManager();
        $em->remove($entity);
        $em->flush();
        
        $this->addFlash("success", "Page was successfully deleted.");

        return $this->redirectToRoute("admin_page_index");
    }
}
