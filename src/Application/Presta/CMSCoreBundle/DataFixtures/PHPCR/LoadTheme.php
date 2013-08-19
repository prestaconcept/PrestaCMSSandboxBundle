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

use Presta\CMSCoreBundle\Doctrine\Phpcr\Zone;
use Presta\CMSCoreBundle\Doctrine\Phpcr\Block;

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
        NodeHelper::createPath($session, '/website/sandbox/theme/creative');
        $root = $manager->find(null, '/website/sandbox/theme/creative');

        $zoneFooterLeft = new Zone('footer_left');
        $zoneFooterLeft->setParentDocument($root);
        $manager->persist($zoneFooterLeft);

        $ipsumString = '<p>Lorem sequat ipsum dolor lorem sit amet, consectetur adipiscing dolor elit. Aenean nisl orci, condimentum.</p>'
            . '<p>Consectetur adipiscing elit aeneane lorem lipsum, condimentum ultrices consequat eu, vehicula mauris lipsum adipiscing lipsum aenean orci lorem.</p>';

        //Create About Us block
        $block = new Block();
        $block->setName('about-us');
        $block->setParent($zoneFooterLeft);
        $block->setType('presta_cms.block.simple');
        $block->setPosition(10);
        $block->setSetting('title', 'About Us');
        $block->setSetting('content', $ipsumString);
        $manager->persist($block);
        $manager->bindTranslation($block, 'en');

        $block->setSetting('title', 'A propos');
        $block->setSetting('content', $ipsumString);
        $manager->bindTranslation($block, 'fr');

        $zoneFooterMiddle = new Zone('footer_middle');
        $zoneFooterMiddle->setParentDocument($root);
        $manager->persist($zoneFooterMiddle);

        //Create About Us block
        $block = new Block();
        $block->setName('links');
        $block->setParent($zoneFooterMiddle);
        $block->setType('presta_cms.block.sitemap');
        $block->setPosition(10);
        $block->setSetting('title', 'Useful Links');
        $block->setSetting('depth', 1);
        $block->setSetting('root_node', '/website/sandbox/page/demo');
        $manager->persist($block);
        $manager->bindTranslation($block, 'en');

        $block->setSetting('title', 'Liens utiles');
        $manager->bindTranslation($block, 'fr');

        $manager->flush();
    }
}
