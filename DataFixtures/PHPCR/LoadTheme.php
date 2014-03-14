<?php
/**
 * This file is part of prestaconcept/symfony-prestacms
 *
 * (c) PrestaConcept <http://www.prestaconcept.net>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */
namespace Presta\CMSSandboxBundle\DataFixtures\PHPCR;

use Doctrine\Common\Persistence\ObjectManager;
use Presta\CMSCoreBundle\DataFixtures\PHPCR\BaseThemeFixture;

use Symfony\Component\Yaml\Parser;

/**
 * @author Nicolas Bastien <nbastien@prestaconcept.net>
 */
class LoadTheme extends BaseThemeFixture
{
    /**
     * {@inheritdoc}
     */
    public function getOrder()
    {
        return 600;
    }

    /**
     * {@inheritdoc}
     */
    public function load(ObjectManager $manager)
    {
        $website = $manager->find(null, '/website/sandbox');

        $yaml = new Parser();
        $configuration = $yaml->parse(file_get_contents(__DIR__ . '/../data/creative_theme.yml'));

        $this->getFactory()->initializeForWebsite($website, $configuration);
    }
}
