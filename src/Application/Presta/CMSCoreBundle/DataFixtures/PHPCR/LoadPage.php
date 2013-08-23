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

        $website = $manager->find(null, '/website/sandbox');

        //création namespace menu
        NodeHelper::createPath($session, '/website/sandbox/page');
        $root = $manager->find(null, '/website/sandbox/page');

        $yaml = new Parser();
        $datas = $yaml->parse(file_get_contents(__DIR__ . '/../data/page.yml'));
        foreach ($datas['pages'] as $pageConfiguration) {

            $pageConfiguration['website'] = $website;
            $pageConfiguration['parent'] = $root;

            $this->getFactory()->create($pageConfiguration);
        }

        $this->manager->flush();
    }
}
