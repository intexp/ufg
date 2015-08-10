<?php

namespace AdminBundle\Controller\Slide;

use AppBundle\Entity\Slide;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\Filesystem\Exception\IOException;
use Symfony\Component\Filesystem\Filesystem;
use Symfony\Component\HttpFoundation\File\UploadedFile;
use Symfony\Component\HttpFoundation\Request;
use AdminBundle\Form\Slide\SlideType;

/**
 * @Route("/slide")
 */
class SlideController extends Controller
{
    /**
     * @Route("/index", name="admin_slide_index")
     */
    public function indexAction()
    {
        $em = $this->getDoctrine()->getManager();
        $entities = $em->getRepository("AppBundle:Slide")->findAll();
        
        return $this->render("admin/slide/index.html.twig", array(
            'entities' => $entities,
        ));
    }

    /**
     * @Route("/create", name="admin_slide_create")
     * @param Request $request
     * @return \Symfony\Component\HttpFoundation\RedirectResponse|\Symfony\Component\HttpFoundation\Response
     */
    public function createAction(Request $request)
    {
        $entity = new Slide();
        
        $languages = $this->container->getParameter("languages");
        
        $form = $this->createForm(new SlideType(array_keys($languages)), $entity);

        if ($request->isMethod('POST')) {
            $form->submit(
                array_merge(
                    $request->request->get($form->getName()),
                    $request->files->get($form->getName(), array())
                )
            );

            if ($form->isValid()) {
                /** @var UploadedFile $uploadedFile */
                $uploadedFile = $form->get('image')->getData();

                $dir = $this->get('kernel')->getRootDir() . "/../web/files/slides";

                $fileName = (string)strtotime('now') . "_" . strtolower(str_replace(" ", "_", $uploadedFile->getClientOriginalName()));

                $uploadedFile->move($dir, $fileName);

                $entity->setImagePath($fileName);

                $em = $this->getDoctrine()->getManager();
                $em->persist($entity);
                $em->flush();

                $this->addFlash("success", "Slide was successfully created.");

                return $this->redirectToRoute("admin_slide_index");
            }
        }
        
        return $this->render("admin/slide/create.html.twig", array(
            "form" => $form->createView(),
        ));
    }

    /**
     * @Route("/edit/{id}", name="admin_slide_edit")
     * @param Request $request
     * @param $id
     * @return \Symfony\Component\HttpFoundation\RedirectResponse|\Symfony\Component\HttpFoundation\Response
     */
    public function editAction(Request $request, $id)
    {
        $em = $this->getDoctrine()->getManager();
        $entity = $em->getRepository("AppBundle:Slide")->find($id);
        
        if (!$entity) {
            throw $this->createNotFoundException("Unable to find slide.");
        }
        
        $languages = $this->container->getParameter("languages");
        
        $form = $this->createForm(new SlideType(array_keys($languages), 'edit'), $entity);

        if ($request->isMethod('POST')) {
            $form->submit(
                array_merge(
                    $request->request->get($form->getName()),
                    $request->files->get($form->getName(), array())
                )
            );

            if ($form->isValid()) {
                /** @var UploadedFile $uploadedFile */
                $uploadedFile = $form->get('image')->getData();

                if (!is_null($uploadedFile)) {
                    $dir = $this->get('kernel')->getRootDir() . "/../web/files/slides";

                    $fileName = (string)strtotime('now') . "_" . strtolower(str_replace(" ", "_", $uploadedFile->getClientOriginalName()));

                    $uploadedFile->move($dir, $fileName);

                    $oldFile = $dir . "/" . $entity->getImagePath();

                    $fs = new Filesystem();
                    if ($fs->exists($oldFile)) {
                        $fs->remove($oldFile);
                    }

                    $entity->setImagePath($fileName);
                }

                $em = $this->getDoctrine()->getManager();
                $em->persist($entity);
                $em->flush();

                $this->addFlash("success", "Slide was successfully updated.");

                return $this->redirectToRoute("admin_slide_edit", array("id" => $entity->getId()));
            }
        }
        
        return $this->render("admin/slide/edit.html.twig", array(
            "form" => $form->createView(),
            "slide" => $entity,
        ));
    }

    /**
     * @Route("/delete/{id}", name="admin_slide_delete")
     * @param $id
     * @return \Symfony\Component\HttpFoundation\RedirectResponse
     */
    public function deleteAction(Request $request, $id)
    {
        $em = $this->getDoctrine()->getManager();
        $entity = $em->getRepository("AppBundle:Slide")->find($id);
        
        if (!$entity) {
            throw $this->createNotFoundException("Unable to find slide.");
        }

        $dir = $this->get('kernel')->getRootDir() . "/../web/files/slides";

        $image = $dir . "/" . $entity->getImagePath();

        $fs = new Filesystem();
        if ($fs->exists($image)) {
            $fs->remove($image);

            $cacheManager = $this->get('liip_imagine.cache.manager');
            $cacheManager->remove("files/slides/" . $entity->getImagePath());

//            $filter = "slide_thumb";
//            $cacheManager->resolve($request, "files/slides/" . $entity->getImagePath(), $filter);
//            $cacheManager->remove($image, $filter);
//            $filter = "slide_main";
//            $cacheManager->resolve($request, "files/slides/" . $entity->getImagePath(), $filter);
//            $cacheManager->remove($image, $filter);
        }

        $em = $this->getDoctrine()->getManager();
        $em->remove($entity);
        $em->flush();
        
        $this->addFlash("success", "Slide was successfully deleted.");

        return $this->redirectToRoute("admin_slide_index");
    }
}
