<?php

namespace AdminBundle\Controller\News;

use AppBundle\Entity\News;
use Doctrine\Common\Persistence\ObjectManager;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\Filesystem\Exception\IOException;
use Symfony\Component\Filesystem\Filesystem;
use Symfony\Component\Finder\Finder;
use Symfony\Component\HttpFoundation\File\UploadedFile;
use Symfony\Component\HttpFoundation\Request;
use AdminBundle\Form\News\NewsType;

/**
 * @Route("/news")
 */
class NewsController extends Controller
{
    /**
     * @Route("/index", name="admin_news_index")
     */
    public function indexAction()
    {
        $em = $this->getDoctrine()->getManager();
        $entities = $em->getRepository("AppBundle:News")->findAll();
        
        return $this->render("admin/news/index.html.twig", array(
            'entities' => $entities,
        ));
    }

    /**
     * @Route("/create", name="admin_news_create")
     * @param Request $request
     * @return \Symfony\Component\HttpFoundation\RedirectResponse|\Symfony\Component\HttpFoundation\Response
     */
    public function createAction(Request $request)
    {
        $entity = new News();
        
        $languages = $this->container->getParameter("languages");
        
        $form = $this->createForm(new NewsType(array_keys($languages)), $entity);

        if ($request->isMethod('POST')) {
            $form->submit(
                array_merge(
                    $request->request->get($form->getName()),
                    $request->files->get($form->getName(), array())
                )
            );

            if ($form->isValid()) {
                /** @var UploadedFile $uploadedFile */
                $uploadedFiles = $form->get('images')->getData();

                $em = $this->getDoctrine()->getManager();
                $em->persist($entity);
                $em->flush();

                foreach ($uploadedFiles as $uploadedFile) {
                    if (!is_null($uploadedFile)) {
                        $rootDir = $this->get('kernel')->getRootDir() . "/../web/files/news";

                        $dir = $rootDir . '/' . $entity->getId();

                        /** @var Filesystem $fs */
                        $fs = new Filesystem();

                        if (!$fs->exists($dir)) {
                            $fs->mkdir($dir);
                        }

                        $fileName = (string)strtotime('now') . "_" . strtolower(str_replace(" ", "_", $uploadedFile->getClientOriginalName()));

                        $uploadedFile->move($dir, $fileName);
                    }
                }


                $this->addFlash("success", "News was successfully created.");

                return $this->redirectToRoute("admin_news_index");
            }
        }
        
        return $this->render("admin/news/create.html.twig", array(
            "form" => $form->createView(),
        ));
    }

    /**
     * @Route("/edit/{id}", name="admin_news_edit")
     * @param Request $request
     * @param $id
     * @return \Symfony\Component\HttpFoundation\RedirectResponse|\Symfony\Component\HttpFoundation\Response
     */
    public function editAction(Request $request, $id)
    {
        $em = $this->getDoctrine()->getManager();

        /** @var News $entity */
        $entity = $em->getRepository("AppBundle:News")->find($id);
        
        if (!$entity) {
            throw $this->createNotFoundException("Unable to find news.");
        }

        $rootDir = $this->get('kernel')->getRootDir() . "/../web/files/news";
        $dir = $rootDir . '/' . $entity->getId();

        $finder = new Finder();
        $finder->files()->in($dir);

        $newsImages = array();
        foreach ($finder as $f) {
            $newsImages[$f->getRelativePathname()] = $f->getRelativePathname();
        }

        $languages = $this->container->getParameter("languages");
        
        $form = $this->createForm(new NewsType(array_keys($languages), 'edit'), $entity);

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
                    $dir = $this->get('kernel')->getRootDir() . "/../web/files/news";

                    $fileName = (string)strtotime('now') . "_" . strtolower(str_replace(" ", "_", $uploadedFile->getClientOriginalName()));

                    $uploadedFile->move($dir, $fileName);

                    $oldFile = $dir . "/" . $entity->getImagePath();

                    $fs = new Filesystem();
                    if ($fs->exists($oldFile)) {
                        $fs->remove($oldFile);

                        $cacheManager = $this->get('liip_imagine.cache.manager');
                        $cacheManager->remove("files/news/" . $entity->getImagePath());
                    }

                    $entity->addImagePath($fileName);
                }

                $em = $this->getDoctrine()->getManager();
                $em->persist($entity);
                $em->flush();

                $this->addFlash("success", "News was successfully updated.");

                return $this->redirectToRoute("admin_news_edit", array("id" => $entity->getId()));
            }
        }
        
        return $this->render("admin/news/edit.html.twig", array(
            "form" => $form->createView(),
            "entity" => $entity,
            "newsImages" => $newsImages,
        ));
    }

    /**
     * @Route("/remove-image/{id}/{imagePath}/{imageKey}", name="admin_news_remove_image")
     * @param $id
     * @param $imagePath
     * @return \Symfony\Component\HttpFoundation\RedirectResponse
     */
    public function removeImageAction($id, $imagePath, $imageKey)
    {
        $rootDir = $this->get('kernel')->getRootDir() . "/../web/files/news";
        $dir = $rootDir . '/' . $id;

        $image = $dir . "/" . $imagePath;

        $fs = new Filesystem();
        if ($fs->exists($image)) {
            $fs->remove($image);

            $cacheManager = $this->get('liip_imagine.cache.manager');
            $cacheManager->remove("files/news/" . $id . "/" . $imagePath);
        }

        $this->addFlash("success", "Image was successfully removed.");

        return $this->redirectToRoute("admin_news_edit", array('id' => $id));
    }

    /**
     * @Route("/delete/{id}", name="admin_news_delete")
     * @param Request $request
     * @param $id
     * @return \Symfony\Component\HttpFoundation\RedirectResponse
     */
    public function deleteAction(Request $request, $id)
    {
        $em = $this->getDoctrine()->getManager();
        $entity = $em->getRepository("AppBundle:News")->find($id);
        
        if (!$entity) {
            throw $this->createNotFoundException("Unable to find news.");
        }

        $rootDir = $this->get('kernel')->getRootDir() . "/../web/files/news";
        $dir = $rootDir . '/' . $id;

        $fs = new Filesystem();

        $finder = new Finder();
        $finder->files()->in($dir);

        foreach ($finder as $f) {
            $image = $dir . "/" . $f->getRelativePathname();

            if ($fs->exists($image)) {
                $fs->remove($image);

                $cacheManager = $this->get('liip_imagine.cache.manager');
                $cacheManager->remove("files/news/" . $id . "/" . $image);
            }
        }

        if ($fs->exists($dir)) {
            $fs->remove($dir);
        }

        $em = $this->getDoctrine()->getManager();
        $em->remove($entity);
        $em->flush();
        
        $this->addFlash("success", "News was successfully deleted.");

        return $this->redirectToRoute("admin_news_index");
    }
}
