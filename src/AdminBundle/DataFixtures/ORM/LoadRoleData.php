<?php

namespace AdminBundle\DataFixtures\ORM;

use Doctrine\Common\DataFixtures\AbstractFixture;
use Doctrine\Common\DataFixtures\OrderedFixtureInterface;
use Doctrine\Common\Persistence\ObjectManager;
use AdminBundle\Entity\Role;

class LoadRoleData extends AbstractFixture implements OrderedFixtureInterface
{
    /**
     * {@inheritDoc}
     */
    public function load(ObjectManager $manager)
    {
        $superAdmin = new Role();
        $superAdmin->setName("superadmin");
        $superAdmin->setRole("ROLE_SUPER_ADMIN");
        
        $manager->persist($superAdmin);

        $admin = new Role();
        $admin->setName("admin");
        $admin->setRole("ROLE_ADMIN");

        $manager->persist($admin);

        $manager->flush();
        
        $this->addReference('super-admin-role', $superAdmin);
        $this->addReference('admin-role', $admin);
    }

    /**
     * {@inheritDoc}
     */
    public function getOrder()
    {
        return 1;
    }
}