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

use FOS\UserBundle\Doctrine\UserManager;
use Symfony\Component\DependencyInjection\ContainerInterface;
use Symfony\Component\DependencyInjection\ContainerAwareInterface;

class LoadUserData extends AbstractFixture implements ContainerAwareInterface, OrderedFixtureInterface
{
    /**
     * @var ContainerInterface
     */
    private $container;

    /**
     * @param ContainerInterface $container
     */
    public function setContainer(ContainerInterface $container = null)
    {
        $this->container = $container;
    }

    /**
     * {@inheritDoc}
     */
    public function getOrder()
    {
        return 2;
    }

    /**
     * @return UserManager
     */
    protected function getUserManager()
    {
        return $this->container->get('fos_user.user_manager');
    }

    /**
     * {@inheritDoc}
     */
    public function load(ObjectManager $manager)
    {
        $userManager = $this->getUserManager();

        /**
         * admin
         */
        $userAdmin = $userManager->createUser();
        $userAdmin->setUsername('admin')
            ->setEmail('admin@prestaconcept.net')
            ->setPlainPassword('admin')
            ->setEnabled(true)
            ->addGroup($manager->merge($this->getReference('admin-group')))
            ->setSuperAdmin(true)
            ;

        $userManager->updateUser($userAdmin, true);

        $user = $userManager->createUser();
        $user
            ->setUsername('user')
            ->setEmail('user@prestaconcept.net')
            ->setPlainPassword('user')
            ->setEnabled(true)
            ->addRole('ROLE_ADMIN_CMS_ACCESS');

        $userManager->updateUser($user, true);
    }
}
