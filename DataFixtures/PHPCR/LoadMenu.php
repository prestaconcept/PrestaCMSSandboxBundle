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
use PHPCR\Util\NodeHelper;
use Symfony\Component\Yaml\Parser;
use Presta\CMSCoreBundle\DataFixtures\PHPCR\BaseMenuFixture;

/**
 * @author Nicolas Bastien <nbastien@prestaconcept.net>
 */
class LoadMenu extends BaseMenuFixture
{
    /**
     * {@inheritdoc}
     */
    public function getOrder()
    {
        return 300;
    }

    /**
     * {@inheritdoc}
     */
    public function load(ObjectManager $manager)
    {
        $this->manager = $manager;
        $session = $manager->getPhpcrSession();

        NodeHelper::createPath($session, '/website/sandbox/menu');
        $root = $manager->find(null, '/website/sandbox/menu');

        $configuration = array(
            'parent'    => $root,
            'name'      => 'main',
            'title'     => array(
                'en' => 'Main navigation',
                'fr' => 'Menu principal'
            ),
            'children_content_path' => '/website/sandbox/page',
            'children'              => array()
        );

        $yaml = new Parser();
        $datas = $yaml->parse(file_get_contents(__DIR__ . '/../data/page.yml'));
        foreach ($datas['pages'] as $key => $pageConfiguration) {
            if (isset($pageConfiguration['in_navigation']) && $pageConfiguration['in_navigation'] === true) {
                if (isset($pageConfiguration['meta']['title'])) {
                    $pageConfiguration['title'] = $pageConfiguration['meta']['title'];
                }

                $configuration['children'][] = $pageConfiguration;
            }
        }

        $main = $this->getFactory()->create($configuration);
        $main->setChildrenAttributes(array("class" => "nav"));

        $manager->flush();
    }
}
