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

//    /**
//     * @param ObjectManager $manager
//     */
//    public function load(ObjectManager $manager)
//    {
//        $this->manager = $manager;
//
//        $yaml = new Parser();
//        $datas = $yaml->parse(file_get_contents(__DIR__ . '/../data/faq.yml'));
//        foreach ($datas['category'] as $categoryConfiguration) {
//            //var_dump($categoryConfiguration);die;
//            $this->createFAQCategory($categoryConfiguration);
//        }
//
//        $this->manager->flush();
//    }

    /**
     * @param  array $configuration
     * @return FAQCategory
     */
    protected function createFAQCategory(array $configuration)
    {
        $configuration += array(
            'title' => array('en' => null, 'fr' => null),
            'enabled'   => true,
            'position' => null,
            'faqs' => array()
        );

        $faqCategory = new FAQCategory();
        $faqCategory->setLocale('en');
        $faqCategory->setTitle($configuration['title']['en']);
        $faqCategory->setEnabled($configuration['enabled']);
        $faqCategory->setPosition($configuration['position']);

        $faqCategory->addTranslation(new FAQCategory\Translation('fr', 'title', $configuration['title']['fr']));

        $this->manager->persist($faqCategory);

        foreach ($configuration as $position => $faqConfiguration) {
            $faqConfiguration['position'] = $position;
            $faq = $this->createFAQ($faqConfiguration);
            $faq->setCategory($faqCategory);

            $this->manager->persist($faq);
        }

        return $faqCategory;
    }

    /**
     * @param  array $configuration
     * @return FAQ
     */
    protected function createFAQ(array $configuration)
    {
        $configuration += array(
            'question' => array('en' => null, 'fr' => null),
            'answer' => array('en' => null, 'fr' => null),
            'position' => 10,
            'enabled' => true
        );

        $faq = new FAQ();
        $faq->setLocale('en');
        $faq->setQuestion($configuration['question']['en']);
        $faq->setAnswer($configuration['answer']['en']);
        $faq->setEnabled($configuration['enabled']);
        $faq->setPosition($configuration['position']);

        return $faq;
    }
} 