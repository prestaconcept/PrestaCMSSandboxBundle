<?php
/**
 * This file is part of the Presta Bundle project.
 *
 * @author     Nicolas Bastien <nbastien@prestaconcept.net>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */
namespace Application\Presta\CMSCoreBundle\DataFixtures\PHPCR;

use Doctrine\Common\DataFixtures\AbstractFixture;
use Doctrine\Common\DataFixtures\OrderedFixtureInterface;
use Doctrine\Common\Persistence\ObjectManager;
use Symfony\Component\DependencyInjection\ContainerAwareInterface;
use Symfony\Component\DependencyInjection\ContainerInterface;
use PHPCR\Util\NodeHelper;

use Symfony\Cmf\Bundle\MenuBundle\Document\MenuNode;
use Symfony\Cmf\Bundle\MenuBundle\Document\MultilangMenuNode;

use Presta\CMSCoreBundle\Document\Website;
use Presta\CMSCoreBundle\Document\Website\Theme;

/**
 * @author     Nicolas Bastien <nbastien@prestaconcept.net>
 */
class LoadMenu extends AbstractFixture implements ContainerAwareInterface, OrderedFixtureInterface
{
    /**
     * {@inheritdoc}
     */
    public function getOrder()
    {
        return 30;
    }

    /**
     * @param \Symfony\Component\DependencyInjection\ContainerInterface $container
     */
    public function setContainer(ContainerInterface $container = null)
    {
        $this->container = $container;
    }

    /**
     * Création des menus de navigation
     *
     * @param \Doctrine\Common\Persistence\ObjectManager $manager
     */
    public function load(ObjectManager $manager)
    {
        $session = $manager->getPhpcrSession();

        //création namespace menu
        NodeHelper::createPath($session, '/website/sandbox/menu');
        $root = $manager->find(null, '/website/sandbox/menu');
        $content_path = '/website/sandbox/page';

        $main = $this->createMenuItem($manager, $root, 'main', array('en' => 'Homepage', 'fr' => 'Accueil'), $manager->find(null, "$content_path/home"));
        $main->setChildrenAttributes(array("class" => "menu_main"));

        $about = $this->createMenuItem($manager, $main, 'about', array('en' => 'About', 'fr' => 'A propos'), $manager->find(null, "$content_path/about"));

        $manager->flush();
    }

    /**
     * @see CMF-Sandbox
     * @return a Navigation instance with the specified information
     */
    protected function createMenuItem($dm, $parent, $name, $label, $content, $uri = null, $route = null)
    {
        $menuitem = is_array($label) ? new MultilangMenuNode() : new MenuNode();
        $menuitem->setParent($parent);
        $menuitem->setName($name);

        $dm->persist($menuitem); // do persist before binding translation

        if (null !== $content) {
            $menuitem->setContent($content);
        } else if (null !== $uri) {
            $menuitem->setUri($uri);
        } else if (null !== $route) {
            $menuitem->setRoute($route);
        }

        if (is_array($label)) {
            foreach ($label as $locale => $l) {
                $menuitem->setLabel($l);
                $dm->bindTranslation($menuitem, $locale);
            }
        } else {
            $menuitem->setLabel($label);
        }

        return $menuitem;
    }
}