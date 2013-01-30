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

use Presta\CMSCoreBundle\Document\Website;

/**
 * @author     Nicolas Bastien <nbastien@prestaconcept.net>
 */
class LoadWebsite extends AbstractFixture implements ContainerAwareInterface, OrderedFixtureInterface
{
    /**
     * {@inheritdoc}
     */
    public function getOrder()
    {
        return 10;
    }

    /**
     * @param \Symfony\Component\DependencyInjection\ContainerInterface $container
     */
    public function setContainer(ContainerInterface $container = null)
    {
        $this->container = $container;
    }

    public function load(ObjectManager $manager)
    {
        // Get the base path name to use from the configuration
        $session = $manager->getPhpcrSession();
        $basePath = DIRECTORY_SEPARATOR . Website::WEBSITE_PREFIX;

        // Create the path in the repository
        NodeHelper::createPath($session, $basePath);

        // Create a new document using StaticContent from the CMF ContentBundle
        $website = new Website();
        $website->setPath($basePath . DIRECTORY_SEPARATOR . 'sandbox');
        $website->setAvailableLocales(array('fr', 'en'));

        $website->setDefaultLocale('fr');
        $website->setActive(true);
        $website->setDefault(true);
        $website->setTheme('default');
        $website->setName('sandbox');

        $manager->persist($website);

        $manager->bindTranslation($website, 'fr');
        $manager->bindTranslation($website, 'en');

        // Commit $document and $block to the database
        $manager->flush();
    }

}