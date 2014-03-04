<?php
/**
 * This file is part of the PrestaCMSSandboxBundle
 *
 * (c) PrestaConcept <www.prestaconcept.net>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */
namespace Presta\CMSSandboxBundle\DataFixtures\ORM;

use Doctrine\Common\DataFixtures\OrderedFixtureInterface;
use Doctrine\Common\Persistence\ObjectManager;
use Presta\CMSFAQBundle\Entity\FAQ;
use Presta\CMSFAQBundle\Entity\FAQCategory;
use Presta\SonataAdminExtendedBundle\Fixture\Loader\AbstractYmlLoader;

/**
 * @author Nicolas Bastien <nbastien@prestaconcept.net>
 */
class LoadFAQ extends AbstractYmlLoader implements OrderedFixtureInterface
{
    /**
     * @param ObjectManager $manager
     */
    public function load(ObjectManager $manager)
    {
        $this->fileDir  = __DIR__ . '/../data/faq/faq.yml';
        $this->class    = '\Presta\CMSFAQBundle\Entity\FAQ';

        parent::load($manager);

        $this->fileDir  = __DIR__ . '/../data/faq/faq_category.yml';
        $this->class    = '\Presta\CMSFAQBundle\Entity\FAQCategory';

        parent::load($manager);
    }

    /**
     * {@inheritDoc}
     */
    public function getOrder()
    {
        return 50;
    }
}
