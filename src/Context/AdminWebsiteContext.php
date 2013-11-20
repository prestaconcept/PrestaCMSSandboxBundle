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
 * @author David Epely <depely@prestaconcept.net>
 */
class AdminWebsiteContext extends BehatContext
{
    /**
     * @Then /^I should see (\d+) websites$/
     */
    public function iShouldSeeWebsites($arg1)
    {
        $this->getMainContext()->assertNumElements($arg1, 'table > tbody tr');
    }

    /**
     * @When /^I follow "([^"]*)" website "([^"]*)"$/
     */
    public function iFollowLinkWebsite($arg1, $arg2)
    {
        $session = $this->getMainContext()->getSession();
        $element = $session->getPage()->find(
            'css',
            ".sonata-ba-list table tbody tr:contains($arg1) a:contains($arg2)"
        );

        if (null === $element) {
            throw new \InvalidArgumentException(sprintf('Could not find "%s" Website or "%s" link', $arg1, $arg2));
        }

        $element->click();
    }

    /**
     * @Given /^I should see a link with selected locale "([^"]*)"$/
     */
    public function iShouldSeeALinkWithSelectedLocale($arg1)
    {
        $this->getMainContext()->assertElementContainsText("ul li.locale.active", $arg1);
    }

    /**
     * @Then /^I should see the sandbox website configuration$/
     */
    public function iShouldSeeTheSandboxWebsiteConfiguration()
    {
        $this->getMainContext()->assertNumElements(6, ".sonata-ba-show table tr");
    }

    /**
     * @Then /^I should see the form to edit "([^"]*)" website$/
     */
    public function iShouldSeeTheFormToEditWebsite($arg1)
    {
        $this->getMainContext()->assertElementContainsText("ul.breadcrumb li:last-child", $arg1);
        $this->getMainContext()->assertElementContainsText(".sonata-ba-form form label", "Theme");
    }
}
