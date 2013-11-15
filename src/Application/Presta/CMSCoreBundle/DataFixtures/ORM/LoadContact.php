<?php
/**
 * This file is part of the PrestaCMSContactBundle.
 *
 * (c) PrestaConcept <http://www.prestaconcept.net>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Application\Presta\CMSCoreBundle\DataFixtures\ORM;

use Doctrine\Common\DataFixtures\OrderedFixtureInterface;
use Doctrine\Common\Persistence\ObjectManager;
use Presta\CMSFAQBundle\Entity\FAQ;
use Presta\CMSFAQBundle\Entity\FAQCategory;
use Presta\SonataAdminExtendedBundle\Fixture\Loader\AbstractYmlLoader;
use Symfony\Component\Yaml\Parser;

/**
 * @author Nicolas Bastien <nbastien@prestaconcept.net>
 */
class LoadContact extends AbstractYmlLoader implements OrderedFixtureInterface
{
    /**
     * @param ObjectManager $manager
     */
    public function load(ObjectManager $manager)
    {
        $this->fileDir  = __DIR__ . '/../data/contact/contact.yml';
        $this->class    = '\Presta\CMSContactBundle\Doctrine\Orm\Contact';

        parent::load($manager);

        $this->fileDir  = __DIR__ . '/../data/contact/message.yml';
        $this->class    = '\Presta\CMSContactBundle\Doctrine\Orm\Message';

        parent::load($manager);
    }

    /**
     * {@inheritDoc}
     */
    public function getOrder()
    {
        return 40;
    }
}
