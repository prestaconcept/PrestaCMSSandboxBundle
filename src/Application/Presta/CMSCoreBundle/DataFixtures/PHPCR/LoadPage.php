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
use Presta\CMSCoreBundle\DataFixtures\PHPCR\BasePageFixture;

/**
 * @author     Nicolas Bastien <nbastien@prestaconcept.net>
 */
class LoadPage extends BasePageFixture
{
    /**
     * Création des pages de contenu
     *
     * @param \Doctrine\Common\Persistence\ObjectManager $manager
     */
    public function load(ObjectManager $manager)
    {
        $this->manager = $manager;
        $session = $manager->getPhpcrSession();

        //création namespace menu
        NodeHelper::createPath($session, '/website/sandbox/page');
        $root = $manager->find(null, '/website/sandbox/page');

        $yaml = new Parser();
        $datas = $yaml->parse(file_get_contents(__DIR__ . '/../data/page.yml'));
        foreach ($datas['pages'] as $page) {
            $this->createPage($page, $root);
        }

        $this->manager->flush();
    }

    /**
     * {@inherit}
     */
    protected function configureZones(array $page)
    {
        if (!is_null($page['zones'])) {
            return $page;
        }
        if ($page['template'] == 'default') {
            $page['zones'] = array(
                'content' => array(
                    10 => array('name' => 'main', 'type' => 'presta_cms.block.simple')
                ),
            );
            if (count($page['children']) > 1) {
                $page['zones']['content'][20] = array('name' => 'children', 'type' => 'presta_cms.block.page_children');
            }
        }
        if ($page['template'] == 'left-sidebar') {
            $page['zones'] = array(
                'content' => array(
                    10 => array('name' => 'main', 'type' => 'presta_cms.block.simple')
                ),
                'left' => array(
                    10 => array('type' => 'presta_cms.block.simple'),
                    20 => array('type' => 'presta_cms.block.simple')
                )
            );
        }

        return $page;
    }

    /**
     * {@inherit}
     */
    protected function configureBlock(array $block)
    {
        $block = parent::configureBlock($block);

        if (count($block['settings'])) {
            return $block;
        }

        switch ($block['type']) {
            case 'presta_cms.block.simple':
                $block['settings'] = array(
                    'title' => 'This is a paragraph block',
                    'content' => 'Lorem ipsum dolor sit amet, consectetur adipiscing elit. Maecenas et auctor est. Vivamus a imperdiet ante. Mauris ut dapibus tellus. Etiam vel enim justo, sit amet vulputate sem. Phasellus eleifend laoreet congue. Sed eu magna nunc, vel porttitor elit. ',
                );
                break;
            case 'presta_cms.block.page_children':
                $block['settings'] = array(
                    'title' => 'This is a page children block',
                    'content' => 'This block displays all page children, each child rendering can be customize by taking advantage of the pages types possibilities.'
                );
                break;
        }
        $block['is_editable'] = true;

        return $block;
    }
}
