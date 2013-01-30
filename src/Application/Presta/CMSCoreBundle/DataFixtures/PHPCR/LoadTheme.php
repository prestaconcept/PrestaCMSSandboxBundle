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

use Presta\CMSCoreBundle\Document\Zone;
use Presta\CMSCoreBundle\Document\Block;

/**
 * @author     Nicolas Bastien <nbastien@prestaconcept.net>
 */
class LoadTheme extends AbstractFixture implements ContainerAwareInterface, OrderedFixtureInterface
{
    /**
     * {@inheritdoc}
     */
    public function getOrder()
    {
        return 60;
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
        NodeHelper::createPath($session, '/website/sandbox/theme/default');
        $root = $manager->find(null, '/website/sandbox/theme/default');

        $zone = new Zone('top');
        $zone->setParentDocument($root);
        $manager->persist($zone);

        //Create top block
        $block = new Block();
        $block->setName('main');
        $block->setParent($zone);
        $block->setType('presta_cms.block.simple');
        $block->setPosition(10);
        $block->setSetting('title', 'Welcome on this sandbox');
        $block->setSetting('content', '<p>This is an early release and we are still working on it</p>');

        $manager->persist($block);

        $manager->bindTranslation($block, 'en');

        $block->setSetting('title', 'Bienvenue sur cette démonstration');
        $block->setSetting('content', '<p>Ceci est une démo du Presta CMS</p><p>Nous sommes encore en plein développement merci de votre compréhension</p>');
        $manager->bindTranslation($block, 'fr');

        $manager->flush();
    }
}