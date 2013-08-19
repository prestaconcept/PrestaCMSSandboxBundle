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

use Presta\CMSCoreBundle\DataFixtures\PHPCR\BaseMenuFixture;

use Presta\CMSCoreBundle\Doctrine\Phpcr\Website;

/**
 * @author     Nicolas Bastien <nbastien@prestaconcept.net>
 */
class LoadMenu extends BaseMenuFixture
{
    /**
     * Création des menus de navigation
     *
     * @param \Doctrine\Common\Persistence\ObjectManager $manager
     */
    public function load(ObjectManager $manager)
    {
        $this->manager = $manager;
        $session = $manager->getPhpcrSession();

        //Create namespace menu
        NodeHelper::createPath($session, '/website/sandbox/menu');
        $root = $manager->find(null, '/website/sandbox/menu');
        $contentPath = '/website/sandbox/page';

        $main = $this->createNavigationRootNode($root, 'main', array('en' => 'Main navigation', 'fr' => 'Menu principal'), $contentPath);
        $main->setChildrenAttributes(array("class" => "nav"));

        $yaml = new Parser();
        $datas = $yaml->parse(file_get_contents(__DIR__ . '/../data/page.yml'));
        foreach ($datas['pages'] as $page) {
            $this->createMenuForPage($main, $page, $contentPath);
        }

        $singlePages = $this->createNavigationRootNode($root, 'single_pages', array('en' => 'Singles Pages', 'fr' => 'Pages simples'), $contentPath);

        $manager->flush();
    }
}
