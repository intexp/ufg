<?php

namespace AppBundle\Controller;

use AppBundle\Form\ContactType;
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
     * @param Request $request
     * @return \Symfony\Component\HttpFoundation\Response
     */
    public function contactAction(Request $request)
    {
        $form = $this->createForm(new ContactType());

        $form->handleRequest($request);

        if ($form->isValid()) {
            $data = $form->getData();

            $message = \Swift_Message::newInstance()
                ->setSubject('UFG | Contact')
                ->setFrom('giorgi.bichinashvili@gmail.com')
                ->setTo('giorgiauuu@gmail.com')
                ->setBody(
                    $this->renderView(
                        'public/main/contact_mail.html.twig',
                        array(
                            'data' => $data,
                        )
                    ),
                    'text/html'
                )
            ;

            $result = $this->get('mailer')->send($message);

            $this->addFlash('contact', 'Your email has been sent! Thanks!');

            return $this->redirectToRoute("contact");
        }

        return $this->render('public/main/contact.html.twig', array(
            'form' => $form->createView(),
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
