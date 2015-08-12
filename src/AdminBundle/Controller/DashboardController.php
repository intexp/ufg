<?php

namespace AdminBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;

class DashboardController extends Controller
{
    /**
     * @Route("/dashboard", name="admin_dashboard")
     * @Route("/")
     */
    public function dashboardAction()
    {
        $em = $this->getDoctrine()->getManager();

        $repository = $em->getRepository('AppBundle:Slide');
        $slides = $repository->findBy(array(), array(), 3, 0);
        $slidesCount = $repository->createQueryBuilder('x')
            ->select('count(x.id)')
            ->getQuery()
            ->getSingleScalarResult();


        $repository = $em->getRepository('AppBundle:Page');
        $pages = $repository->findBy(array(), array(), 3, 0);
        $pagesCount = $repository->createQueryBuilder('x')
            ->select('count(x.id)')
            ->getQuery()
            ->getSingleScalarResult();


        $repository = $em->getRepository('AppBundle:Company');
        $companies = $repository->findBy(array(), array(), 3, 0);
        $companiesCount = $repository->createQueryBuilder('x')
            ->select('count(x.id)')
            ->getQuery()
            ->getSingleScalarResult();

        return $this->render('admin/dashboard.html.twig', array(
            'slides' => $slides,
            'slidesCount' => $slidesCount,
            'pages' => $pages,
            'pagesCount' => $pagesCount,
            'companies' => $companies,
            'companiesCount' => $companiesCount,
        ));
    }
}
