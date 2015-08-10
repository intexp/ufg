<?php

namespace AppBundle\Controller;

use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;

/**
 * @Route("/company")
 */
class CompanyController extends Controller
{
    /**
     * @Route("/index", name="company_index")
     * @return \Symfony\Component\HttpFoundation\Response
     */
    public function indexAction()
    {
        $em = $this->getDoctrine()->getManager();
        $companies = $em->getRepository("AppBundle:Slide")->findAll();

        $rows = array_chunk($companies, 4);

        return $this->render('public/company/index.html.twig', array(
            "companies" => $rows,
        ));
    }

    /**
     * @Route("/{slug}", name="company_show")
     * @return \Symfony\Component\HttpFoundation\Response
     */
    public function showAction($slug)
    {
        return $this->render('public/company/show.html.twig', array(
        ));
    }
}
