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
use Presta\CMSCoreBundle\Doctrine\Phpcr\Website;

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

        //création namespace menu
        NodeHelper::createPath($session, '/website/sandbox/route');
        $root = $manager->find(null, '/website/sandbox/route');

        //Routing home
        $configuration = array(
            'parent' => $root,
            'content_path' => '/website/sandbox/page/home',
            'name' => 'en',
            'locale' => 'en'
        );
        $home = $this->getFactory()->create($configuration);
        $configuration['name'] = 'fr';
        $configuration['locale'] = 'fr';
        $homeFr = $this->getFactory()->create($configuration);

        $yaml = new Parser();
        $datas = $yaml->parse(file_get_contents(__DIR__ . '/../data/page.yml'));
        foreach ($datas['pages'] as $pageConfiguration) {
            if ($pageConfiguration['name'] == 'home') {
                continue;
            }
            $pageConfiguration['content_path'] = '/website/sandbox/page' . '/' .  $pageConfiguration['name'];
            $pageConfiguration['parent'] = $home;
            $pageConfiguration['locale'] = 'en';
            $this->getFactory()->create($pageConfiguration);

            $pageConfiguration['parent'] = $homeFr;
            $pageConfiguration['locale'] = 'fr';
            $this->getFactory()->create($pageConfiguration);
        }

        $this->manager->flush();
    }
}
