<?php
/**
 * This file is part of prestaconcept/symfony-prestacms
 *
 * (c) PrestaConcept <http://www.prestaconcept.net>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */
namespace Context;

use Behat\Behat\Context\BehatContext;

/**
 * Description of ThemeContext
 *
 * @author David Epely <depely@prestaconcept.net>
 */
class AdminThemeContext extends BehatContext
{
    /**
     * @Then /^I should see (\d+) themes$/
     */
    public function iShouldSeeThemes($arg1)
    {
        $this->getMainContext()->assertNumElements($arg1, '.sonata-ba-content table tbody tr');
    }

    /**
     * @Then /^I should see the creative theme configuration$/
     */
    public function iShouldSeeTheCreativeThemeConfiguration()
    {
        $this->getMainContext()->assertPageContainsText('General informations');

        //Zones
        $this->getMainContext()->assertPageContainsText('top');
        $this->getMainContext()->assertPageContainsText('navigation');
        $this->getMainContext()->assertPageContainsText('content');
        $this->getMainContext()->assertPageContainsText('footer_left');
        $this->getMainContext()->assertPageContainsText('footer_middle');
        $this->getMainContext()->assertPageContainsText('footer_right');

        //Page template
        $this->getMainContext()->assertPageContainsText('default');

        //Block content
        $this->getMainContext()->assertPageContainsText('About US');
        $this->getMainContext()->assertPageContainsText('Latest Tweets');
        $this->getMainContext()->assertPageContainsText(
            'PrestaCMS is an open-source CMS base on Symfony2, CMF and Sonata.'
        );
    }

    /**
     * @Given /^I should see (\d+) locales$/
     */
    public function iShouldSeeLocales($arg1)
    {
        $this->getMainContext()->assertNumElements($arg1, 'ul#website-locale li');
    }
}
