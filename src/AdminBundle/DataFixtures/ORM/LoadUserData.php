<?php

namespace AdminBundle\DataFixtures\ORM;

use Doctrine\Common\DataFixtures\AbstractFixture;
use Doctrine\Common\DataFixtures\OrderedFixtureInterface;
use Doctrine\Common\Persistence\ObjectManager;
use AdminBundle\Entity\User;
use Symfony\Component\DependencyInjection\ContainerAwareInterface;
use Symfony\Component\DependencyInjection\ContainerInterface;

class LoadUserData extends AbstractFixture implements OrderedFixtureInterface, ContainerAwareInterface
{
    /**
     * @var ContainerInterface
     */
    private $container;
    
    /**
     * {@inheritDoc}
     */
    public function setContainer(ContainerInterface $container = null)
    {
        $this->container = $container;
    }

    /**
     * {@inheritDoc}
     */
    public function load(ObjectManager $manager)
    {
        $superAdmin = new User();
        $superAdmin->setUsername('superadmin');
        $superAdmin->setEmail('superadmin@superadmin.com');
        $superAdmin->setIsActive(1);
        $superAdmin->setSalt(null);

        $encoder = $this->container->get('security.password_encoder');

        $password = $encoder->encodePassword($superAdmin, 'superadmin');

        $superAdmin->setPassword($password);

        $superAdmin->addRole($this->getReference('super-admin-role'));
        
        $manager->persist($superAdmin);

        $admin = new User();
        $admin->setUsername('admin');
        $admin->setEmail('admin@admin.com');
        $admin->setIsActive(1);
        $admin->setSalt(null);

        $encoder = $this->container->get('security.password_encoder');

        $password = $encoder->encodePassword($admin, 'admin');

        $admin->setPassword($password);

        $admin->addRole($this->getReference('admin-role'));

        $manager->persist($admin);

        $manager->flush();
    }

    /**
     * {@inheritDoc}
     */
    public function getOrder()
    {
        return 2;
    }
}