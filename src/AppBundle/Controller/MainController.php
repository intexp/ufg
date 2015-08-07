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
        return $this->render('public/main/home.html.twig', array(
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
     */
    public function pageAction($slug)
    {
        return $this->render('public/main/page.html.twig', array(
        ));
    }
}
