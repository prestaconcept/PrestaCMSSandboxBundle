<?php
/**
 * This file is part of the PrestaCMS-Sandbox
 *
 * (c) PrestaConcept <www.prestaconcept.net>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */
namespace Application\Sonata\UserBundle\DataFixtures\ORM;

use Doctrine\Common\DataFixtures\AbstractFixture;
use Doctrine\Common\DataFixtures\OrderedFixtureInterface;
use Doctrine\Common\Persistence\ObjectManager;

use Application\Sonata\UserBundle\Entity\Group;

class LoadGroupData extends AbstractFixture implements OrderedFixtureInterface
{
    /**
     * {@inheritDoc}
     */
    public function load(ObjectManager $manager)
    {
        // admin group
        $adminGroup = new Group('admin');
        $adminGroup->addRole('ROLE_ADMIN');
        $this->addReference('admin-group', $adminGroup);
        $manager->persist($adminGroup);

        // customer group
        $userGroup = new Group('user');
        $userGroup->addRole('ROLE_USER');
        $this->addReference('user-group', $userGroup);
        $manager->persist($userGroup);

        $manager->flush();
    }

    /**
     * {@inheritDoc}
     */
    public function getOrder()
    {
        return 1;
    }
}
