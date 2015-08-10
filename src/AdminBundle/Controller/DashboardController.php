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
        return $this->render('admin/dashboard.html.twig', array(
        ));
    }
}
