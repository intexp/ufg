<?php

namespace AppBundle\Menu;

use Knp\Menu\FactoryInterface;
use Symfony\Component\DependencyInjection\ContainerAware;

class Builder extends ContainerAware
{
    public function mainMenu(FactoryInterface $factory, array $options)
    {
        $locale = $this->container->get('request_stack')->getCurrentRequest()->getLocale();

        $menu = $factory->createItem('root');

        $menu->setChildrenAttributes(array('class' => 'nav navbar-nav'));

        $menu->addChild('Home', array('route' => 'home'));

        $menu->addChild('About us', array(
            'route' => 'page',
            'routeParameters' => array('slug' => 'about')
        ));

        $menu->addChild('Business', array('route' => 'company_index'));

        $menu->addChild('Contact', array('route' => 'contact'));

        return $menu;
    }
}