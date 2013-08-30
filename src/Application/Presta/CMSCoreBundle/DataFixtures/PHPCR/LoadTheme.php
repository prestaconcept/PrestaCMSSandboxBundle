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
        $ipsumString = '<p>Lorem sequat ipsum dolor lorem sit amet, consectetur adipiscing dolor elit. Aenean nisl orci, condimentum.</p>'
            . '<p>Consectetur adipiscing elit aeneane lorem lipsum, condimentum ultrices consequat eu, vehicula mauris lipsum adipiscing lipsum aenean orci lorem.</p>';

        $website = $manager->find(null, '/website/sandbox');

        $configuration = array(
            'name'  => 'creative',
            'zones' => array(
                'footer_left' => array(
                    'name' => 'footer_left',
                    'blocks' => array(
                        array(
                            'type' => 'presta_cms.block.simple',
                            'position' => 10,
                            'settings' => array(
                                'en' => array(
                                   'title' => 'About Us',
                                   'content' => $ipsumString
                                ),
                                'fr' => array(
                                    'title' => 'A propos',
                                    'content' => $ipsumString
                                )
                            )
                        )
                    )
                ),
                'footer_middle' => array(
                    'name' => 'footer_middle',
                    'blocks' => array(
                        array(
                            'type' => 'presta_cms.block.sitemap',
                            'position' => 10,
                            'settings' => array(
                                'en' => array(
                                    'title' => 'Useful Links',
                                    'depth' => 1,
                                    'root_node' => '/website/sandbox/page/demo'
                                ),
                                'fr' => array(
                                    'title' => 'Liens utiles',
                                    'depth' => 1,
                                    'root_node' => '/website/sandbox/page/demo'
                                )
                            )
                        )
                    )
                ),
            )
        );

        $this->container->get('presta_cms.theme.factory')->initializeForWebsite($website, $configuration);
    }
}
