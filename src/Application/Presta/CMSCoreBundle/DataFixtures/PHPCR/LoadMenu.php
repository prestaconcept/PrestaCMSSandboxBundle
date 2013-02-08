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
use Symfony\Component\DependencyInjection\ContainerInterface;
use PHPCR\Util\NodeHelper;
use Symfony\Component\Yaml\Parser;

use Presta\CMSCoreBundle\DataFixtures\PHPCR\BaseMenuFixture;

use Presta\CMSCoreBundle\Document\Website;
use Presta\CMSCoreBundle\Document\Website\Theme;

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
        $content_path = '/website/sandbox/page';

        $main = $this->createMenuNode($root, 'main', array('en' => 'Homepage', 'fr' => 'Accueil'), $manager->find(null, "$content_path/home"));
        $main->setChildrenAttributes(array("class" => "menu_main"));

        $yaml = new Parser();
        $datas = $yaml->parse(file_get_contents(__DIR__ . '/../data/page.yml'));
        foreach ($datas['pages'] as $page) {
            $this->createMenuForPage($main, $page, $content_path);
        }

        //Example using a symfony route
        $this->createMenuNode($main, 'admin-item', 'Admin', null, null, 'sonata_admin_dashboard');
        //Example of external link
        $this->createMenuNode($main, 'prestaconcept-item', 'By PrestaConcept', null, 'http://www.prestaconcept.net/');

        $manager->flush();
    }
}