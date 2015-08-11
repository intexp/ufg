<?php

namespace AppBundle\Controller;

use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;

class MainController extends Controller
{
    /**
     * @Route("/", name="home")
     * @return \Symfony\Component\HttpFoundation\Response
     */
    public function homeAction()
    {
        $em = $this->getDoctrine()->getManager();
        $slides = $em->getRepository("AppBundle:Slide")->findAll();

        return $this->render('public/main/home.html.twig', array(
            "slides" => $slides,
        ));
    }

    /**
     * @Route("/contact", name="contact")
     */
    public function contactAction()
    {
        return $this->render('public/main/contact.html.twig', array(
        ));
    }

    /**
     * @Route("/{slug}", name="page")
     * @param $slug
     * @return \Symfony\Component\HttpFoundation\Response
     */
    public function pageAction($slug)
    {
        $entity = $this->getDoctrine()
            ->getManager()
            ->getRepository('AppBundle:Page')
            ->findOneBy(array('slug' => $slug))
        ;

        if (!$entity) {
            throw $this->createNotFoundException();
        }

        return $this->render('public/main/page.html.twig', array(
            'entity' => $entity,
        ));
    }
}
