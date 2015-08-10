<?php

namespace AdminBundle\Controller;

use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Security\Core\User\UserInterface;
use Symfony\Component\Security\Core\Authentication\Token\UsernamePasswordToken;
use AdminBundle\Form\AccountType;
use AdminBundle\Form\ChangePasswordType;
use AdminBundle\Form\Model\ChangePassword;

/**
 * @Route("/account")
 */
class AccountController extends Controller
{
    /**
     * @Route("/edit", name="admin_account_edit")
     * @param Request $request
     * @return \Symfony\Component\HttpFoundation\RedirectResponse|\Symfony\Component\HttpFoundation\Response
     */
    public function editAction(Request $request)
    {
        $user = $this->getUser();
        
        if (!$user) {
            throw $this->createNotFoundException("User not found.");
        }
        
        $form = $this->createForm(new AccountType(), $user);
        
        $form->handleRequest($request);
        
        if ($form->isValid()) {
            $em = $this->getDoctrine()->getManager();
            $em->persist($user);
            $em->flush();

            $this->addFlash("success", "Your account settings has been updated.");

            return $this->redirectToRoute("admin_account_edit");
        }
        
        return $this->render('admin/account/account_settings.html.twig', array(
            'form' => $form->createView(),
        ));
    }

    /**
     * @Route("/change-password", name="admin_change_password")
     * @param Request $request
     * @return \Symfony\Component\HttpFoundation\RedirectResponse|\Symfony\Component\HttpFoundation\Response
     */
    public function changePasswordAction(Request $request)
    {
        $user = $this->getUser();        
        
        if (!$user) {
            throw $this->createNotFoundException("User not found.");
        }
        
        $form = $this->createForm(new ChangePasswordType(), new ChangePassword());
        
        $form->handleRequest($request);
        
        if ($form->isValid()) {
            $data = $form->getData();
            
            $user->setPassword($this->encodePassword($user, $data->getNewPassword()));
            
            $em = $this->getDoctrine()->getManager();
            $em->persist($user);
            $em->flush();
            
            //$this->authenticateUser($user);
            $this->addFlash("success", "Your password has been changed.");

            return $this->redirectToRoute("admin_change_password");
        }        
        
        return $this->render('admin/account/change_password.html.twig', array(
            'form' => $form->createView(),
        ));        
    }

    private function encodePassword($user, $plainPassword)
    {
        $encoder = $this->get('security.password_encoder');

        return $encoder->encodePassword($user, $plainPassword);
    }
    
    private function authenticateUser(UserInterface $user)
    {
        $providerKey = 'user';
        
        $token = new UsernamePasswordToken($user, null, $providerKey, $user->getRoles());
        
        $this->get('security.context')->setToken($token);
    }
}
