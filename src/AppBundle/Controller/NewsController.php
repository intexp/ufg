<?php

namespace AppBundle\Controller;

use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;

/**
 * @Route("/news")
 */
class NewsController extends Controller
{
    /**
     * @Route("/{slug}", name="news_show")
     * @param $slug
     * @return \Symfony\Component\HttpFoundation\Response
     */
    public function showAction($slug)
    {
        $entity = $this->getDoctrine()
            ->getManager()
            ->getRepository('AppBundle:News')
            ->findOneBy(array('slug' => $slug))
        ;

        if (!$entity) {
            throw $this->createNotFoundException();
        }

        return $this->render('public/news/show.html.twig', array(
            'entity' => $entity,
        ));
    }

    /**
     * @Route("/", name="news_index")
     * @return \Symfony\Component\HttpFoundation\Response
     */
    public function indexAction()
    {
        $em = $this->getDoctrine()->getManager();
        $news = $em->getRepository("AppBundle:News")->findAll();

        return $this->render('public/news/index.html.twig', array(
            "newss" => $news,
        ));
    }
}
