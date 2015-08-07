<?php

namespace AppBundle\Controller;

use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;

/**
 * @Route("/company")
 */
class CompanyController extends Controller
{
    /**
     * @Route("/{slug}", name="company_show")
     * @return \Symfony\Component\HttpFoundation\Response
     */
    public function homeAction($slug)
    {
        return $this->render('public/company/show.html.twig', array(
        ));
    }
}
