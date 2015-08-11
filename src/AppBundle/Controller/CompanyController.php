<?php

namespace AppBundle\Controller;

use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;

/**
 * @Route("/business")
 */
class CompanyController extends Controller
{
    /**
     * @Route("/{slug}", name="company_show")
     * @return \Symfony\Component\HttpFoundation\Response
     */
    public function showAction($slug)
    {
        $entity = $this->getDoctrine()
            ->getManager()
            ->getRepository('AppBundle:Company')
            ->findOneBy(array('slug' => $slug))
        ;

        if (!$entity) {
            throw $this->createNotFoundException();
        }
        return $this->render('public/company/show.html.twig', array(
            'entity' => $entity,
        ));
    }

    /**
     * @Route("/", name="company_index")
     * @return \Symfony\Component\HttpFoundation\Response
     */
    public function indexAction()
    {
        $em = $this->getDoctrine()->getManager();
        $companies = $em->getRepository("AppBundle:Company")->findAll();

        $rows = array_chunk($companies, 4);

        return $this->render('public/company/index.html.twig', array(
            "companies" => $rows,
        ));
    }
}
