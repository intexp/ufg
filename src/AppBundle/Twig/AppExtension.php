<?php

namespace AppBundle\Twig;

use Symfony\Component\DependencyInjection\ContainerInterface;

class AppExtension extends \Twig_Extension
{
    /**
     * @var ContainerInterface
     */
    protected $container;

    /**
     * Constructor
     *
     * @param ContainerInterface $container
     */
    public function __construct($container)
    {
        $this->container = $container;
    }

    public function getFilters()
    {
        return array(
            new \Twig_SimpleFilter('localeToLanguageName', array($this, 'localeToLanguageNameFilter')),
        );
    }

    public function localeToLanguageNameFilter($locale)
    {
        $languages = $this->container->getParameter("languages");

        if (isset($languages[$locale])) {
            $language = $languages[$locale];

            return $language['title'];
        }

        return $locale;
    }

    public function getName()
    {
        return 'app_extension';
    }
}