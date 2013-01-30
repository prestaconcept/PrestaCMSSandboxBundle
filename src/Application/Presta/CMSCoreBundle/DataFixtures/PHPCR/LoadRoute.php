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

use Symfony\Cmf\Component\Routing\RouteObjectInterface;
use Symfony\Cmf\Bundle\RoutingExtraBundle\Document\Route;
use Symfony\Cmf\Bundle\RoutingExtraBundle\Document\RedirectRoute;

use Presta\CMSCoreBundle\Document\Website;
use Presta\CMSCoreBundle\Document\Website\Theme;

/**
 * @author     Nicolas Bastien <nbastien@prestaconcept.net>
 */
class LoadRoute extends AbstractFixture implements ContainerAwareInterface, OrderedFixtureInterface
{
    /**
     * @var ObjectManager
     */
    protected $manager;

    /**
     * @var string
     */
    protected $contentPath;

    /**
     * {@inheritdoc}
     */
    public function getOrder()
    {
        return 40;
    }

    /**
     * @param \Symfony\Component\DependencyInjection\ContainerInterface $container
     */
    public function setContainer(ContainerInterface $container = null)
    {
        $this->container = $container;
    }

    /**
     * Création des routes associées au contenu
     *
     * @param \Doctrine\Common\Persistence\ObjectManager $manager
     */
    public function load(ObjectManager $manager)
    {
        $session = $manager->getPhpcrSession();
        $this->contentPath = '/website/sandbox/page';
        $this->manager = $manager;

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
            $this->createRouteForPage($manager, $home, $homeFr, $page, '/website/sandbox/page');
        }

        $this->manager->flush();
    }

    /**
     * Create en and fr route for a content
     *
     * @param $dm
     * @param $parentEn
     * @param $parentFr
     * @param $page
     * @param $contentPath
     */
    protected function createRouteForPage($dm, $parentEn, $parentFr, $page, $contentPath)
    {
        $page += array('url' => array('en' => $page['name'], 'fr' => $page['name']), 'children' => null, 'url-pattern' => null, 'url-default' => null);
        $contentPath .= '/' . $page['name'];
        $content = $dm->find(null, $contentPath);

        $routeEn = $this->createRoute($parentEn, $page['url']['en'], $content, 'en', $page['url-pattern'], $page['url-default']);
        $routeFr = $this->createRoute($parentFr, $page['url']['fr'], $content, 'fr', $page['url-pattern'], $page['url-default']);
        if ($page['children'] != null) {
            foreach ($page['children'] as $child) {
                $this->createRouteForPage($dm, $routeEn, $routeFr, $child, $contentPath);
            }
        }
    }

    /**
     * @param $root
     * @param $name
     * @param $content
     * @param $locale
     * @return \Symfony\Cmf\Bundle\RoutingExtraBundle\Document\Route
     */
    protected function createRoute($root, $name, $content, $locale, $urlPattern = null, $urlDefault = null)
    {
        $route = new Route;
        $route->setPosition($root, $name);
        $route->setDefault('_locale', $locale);
        $route->setRequirement('_locale', $locale);
        if ($urlPattern != null) {
            $route->setVariablePattern($urlPattern);
        }
        if ($urlDefault != null) {
            foreach ($urlDefault as $key => $value) {
                $route->setDefault($key, $value);
            }
        }
        $route->setRouteContent($content);
        $this->manager->persist($route);

        return $route;
    }
}