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

use Doctrine\Common\Persistence\ObjectManager;
use PHPCR\Util\NodeHelper;
use Symfony\Component\Yaml\Parser;

use Presta\CMSCoreBundle\DataFixtures\PHPCR\BaseRouteFixture;

use Presta\CMSCoreBundle\Document\Website;

/**
 * @author     Nicolas Bastien <nbastien@prestaconcept.net>
 */
class LoadRoute extends BaseRouteFixture
{
    /**
     * Création des routes associées au contenu
     *
     * @param \Doctrine\Common\Persistence\ObjectManager $manager
     */
    public function load(ObjectManager $manager)
    {
        $this->manager = $manager;
        $session = $manager->getPhpcrSession();
        $this->contentPath = '/website/sandbox/page';

        //création namespace menu
        NodeHelper::createPath($session, '/website/sandbox/route');
        $root = $manager->find(null, '/website/sandbox/route');

        //Routing home
        $homepage = $manager->find(null, '/website/sandbox/page/home');
        $home = $this->createRoute($root, 'en', $homepage, 'en');
        $homeFr = $this->createRoute($root, 'fr', $homepage, 'fr');

        $yaml = new Parser();
        $datas = $yaml->parse(file_get_contents(__DIR__ . '/../data/page.yml'));
        foreach ($datas['pages'] as $page) {
            if ($page['name'] == 'home') {
                continue;
            }
            $this->createRouteForPage($home, 'en', $page, '/website/sandbox/page');
            $this->createRouteForPage($homeFr, 'fr', $page, '/website/sandbox/page');
        }

        $this->manager->flush();
    }
}
