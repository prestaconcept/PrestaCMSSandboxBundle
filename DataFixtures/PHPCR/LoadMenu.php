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

        //Create namespace menu
        NodeHelper::createPath($session, '/website/symfony-prestacms/menu/main');
        $mainMenu    = $manager->find(null, '/website/symfony-prestacms/menu/main');
        $contentPath = '/website/symfony-prestacms/page';

        $yaml = new Parser();
        $datas = $yaml->parse(file_get_contents(__DIR__ . '/../data/page.yml'));
        foreach ($datas['pages'] as $pageConfiguration) {
            $pageConfiguration['parent'] = $mainMenu;
            if (isset($pageConfiguration['meta']['title'])) {
                $pageConfiguration['title'] = $pageConfiguration['meta']['title'];
            }
            $pageConfiguration['content_path'] = $contentPath . '/' . $pageConfiguration['name'];
            $this->getFactory()->create($pageConfiguration);
        }

        $manager->flush();
    }
}
