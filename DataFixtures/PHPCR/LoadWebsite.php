<?php
/**
 * This file is part of the PrestaCMSSandboxBundle
 *
 * (c) PrestaConcept <www.prestaconcept.net>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */
namespace Presta\CMSSandboxBundle\DataFixtures\PHPCR;

use Doctrine\Common\Persistence\ObjectManager;
use Presta\CMSCoreBundle\DataFixtures\PHPCR\BaseWebsiteFixture;
use PHPCR\Util\NodeHelper;
use Presta\CMSCoreBundle\Doctrine\Phpcr\Website;

/**
 * @author Nicolas Bastien <nbastien@prestaconcept.net>
 */
class LoadWebsite extends BaseWebsiteFixture
{
    /**
     * {@inheritdoc}
     */
    public function getOrder()
    {
        return 100;
    }

    /**
     * {@inheritdoc}
     */
    public function load(ObjectManager $manager)
    {
        // Get the base path name to use from the configuration
        $session = $manager->getPhpcrSession();
        $basePath = DIRECTORY_SEPARATOR . Website::WEBSITE_PREFIX;

        // Create the path in the repository
        NodeHelper::createPath($session, $basePath);

        /**
         * Sandbox website is only useful to test website selector
         */
        $this->getFactory()->create(
            array(
                'path' => $basePath . DIRECTORY_SEPARATOR . 'sandbox',
                'name' => 'sandbox',
                'available_locales' => array('fr', 'en'),
                'default_locale' => 'fr',
                'theme' => 'creative'
            )
        );

        $manager->flush();
    }
}
