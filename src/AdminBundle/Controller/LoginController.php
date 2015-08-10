<?php

namespace AdminBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;

class LoginController extends Controller
{
    /**
     * @Route("/login", name="admin_login")
     */
    public function loginAction()
    {
        if ($this->get('security.context')->isGranted('ROLE_ADMIN')) {
            return $this->redirect($this->generateUrl("admin_dashboard"));
        }

        $authenticationUtils = $this->get('security.authentication_utils');

        $error = $authenticationUtils->getLastAuthenticationError();

        $lastUsername = $authenticationUtils->getLastUsername();

        return $this->render('admin/login.html.twig', array(
            'error' => $error,
            'last_username' => $lastUsername,
        ));
    }

    /**
     * @Route("/login_check", name="admin_login_check")
     */
    public function loginCheckAction()
    {
    }

    /**
     * @Route("/logout", name="admin_logout")
     */
    public function logoutAction()
    {
    }
}
