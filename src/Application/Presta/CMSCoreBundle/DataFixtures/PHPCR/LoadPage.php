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
use Symfony\Component\Yaml\Parser;

use Presta\CMSCoreBundle\Document\Website;
use Presta\CMSCoreBundle\Document\Theme;
use Presta\CMSCoreBundle\Document\Page;
use Presta\CMSCoreBundle\Document\Zone;
use Presta\CMSCoreBundle\Document\Block;

/**
 * @author     Nicolas Bastien <nbastien@prestaconcept.net>
 */
class LoadPage extends AbstractFixture implements ContainerAwareInterface, OrderedFixtureInterface
{
    protected $manager;

    /**
     * {@inheritdoc}
     */
    public function getOrder()
    {
        return 20;
    }

    /**
     * @param \Symfony\Component\DependencyInjection\ContainerInterface $container
     */
    public function setContainer(ContainerInterface $container = null)
    {
        $this->container = $container;
    }

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
            $this->createPage($page['name'], $root, $page['type'], $page['template'], $page['meta'], $page['zones'], $page['children']);
        }

        $this->manager->flush();
    }

    /**
     * @param $name
     * @param $parent
     * @param $type
     * @param $template
     * @param $data
     * @param $blocks
     * @return \Presta\CMSCoreBundle\Document\Page
     */
    protected function createPage($name, $parent, $type, $template, $meta, $zones = null, $children = null)
    {
        $meta += array('title' => array('en' => '', 'fr' => ''), 'keywords' => array('en' => '', 'fr' => ''), 'description' => array('en' => '', 'fr' => ''));
        $page = new Page();
        $page->setName($name);
        $page->setParent($parent);
        $page->setIsActive(true);
        $page->setType($type);
        $page->setTemplate($template);
        $this->manager->persist($page);
        $page->setTitle($meta['title']['en']);
        $page->setMetaDescription($meta['description']['en']);
        $page->setMetaKeywords($meta['keywords']['en']);
        $this->manager->bindTranslation($page, 'en');
        $page->setTitle($meta['title']['fr']);
        $page->setMetaDescription($meta['description']['fr']);
        $page->setMetaKeywords($meta['keywords']['fr']);
        $this->manager->bindTranslation($page, 'fr');


        //Creation des blocks
        if ($zones != null) {
            foreach ($zones as $zoneName => $zone) {
                $pageZone = new Zone();
                $pageZone->setParentDocument($page);
                $pageZone->setName($zoneName);
                $this->manager->persist($pageZone);
                foreach ($zone as $position => $blockConfiguration) {
                    $blockConfiguration += array('name' => null, 'is_editable' => false, 'is_deletable' => false, 'settings' => array());

                    $block = new Block();
                    $block->setParent($pageZone);
                    $block->setType($blockConfiguration['type']);
                    if (strlen($blockConfiguration['name'])) {
                        $block->setName($blockConfiguration['name']);
                    } else {
                        $block->setName($blockConfiguration['type'] . '-' . $position);
                    }
                    $block->setIsEditable($blockConfiguration['is_editable']);
                    $block->setIsDeletable($blockConfiguration['is_deletable']);
                    $block->setPosition($position);
                    $block->setIsActive(true);
                    $block->setSettings($blockConfiguration['settings']);
                    $this->manager->persist($block);
                }
            }
        }

        return $page;
    }
}