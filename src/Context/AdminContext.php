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

use Behat\MinkExtension\Context\MinkContext;

/**
 * @author Nicolas Bastien <nbastien@prestaconcept.net>
 */
class AdminContext extends MinkContext
{
    /**
     * @When /^I am connected with "([^"]*)" and "([^"]*)" on "([^"]*)" I should see "([^"]*)"$/
     *
     * @param string $login
     * @param string $pwd
     * @param string $url
     * @param string $match
     */
    public function iAmConnectedWithAndOnIShouldSee($login, $pwd, $url, $match)
    {
        $this->iAmConnectedWithOn($login, $pwd, $url);
        $this->assertPageContainsText($match);
    }

    /**
     * @Given /^I am connected with "([^"]*)" and "([^"]*)" on "([^"]*)"$/
     *
     * @param string $login
     * @param string $pwd
     * @param string $url
     */
    public function iAmConnectedWithOn($login, $pwd, $url)
    {
        $this->visit($url);
        $this->fillField('_username', $login);
        $this->fillField('_password', $pwd);
        $this->pressButton('_submit');
        $this->visit($url);
    }

    /**
     * @Given /^I follow link "([^"]*)" with class "([^"]*)"$/
     */
    public function iFollowLinkWithClass($text, $class)
    {
        $link = $this->getSession()->getPage()
            ->find('xpath', sprintf("//*[@class='%s' and text() = '%s']", $class, $text));

        if (!$link) {
            throw new ExpectationException(
                sprintf('Unable to follow the link with class: %s and text: %s', $class, $text),
                $this->getSession()
            );
        }

        $link->click();
    }

    /**
     * @Given /^I follow the first link of section "([^"]*)"$/
     */
    public function iFollowTheFirstLinkOfSection($class)
    {
        $link = $this->getSession()->getPage()->find('xpath', sprintf("//*[@class='%s']/a", $class));

        if (!$link) {
            throw new ExpectationException(
                sprintf('Unable to follow the nested link with class: %s', $class),
                $this->getSession()
            );
        }

        $link->click();
    }

    /**
     * @When /^I follow dashboard "([^"]*)" link "([^"]*)"$/
     */
    public function iFollowDashboardLink($arg1, $arg2)
    {
        $session = $this->getMainContext()->getSession();
        $element = $session->getPage()->find(
            'css',
            ".block-content-block h4:contains($arg1) + a:contains($arg2)"
        );

        if (null === $element) {
            throw new \InvalidArgumentException(sprintf('Could not find "%s" Website or "%s" link', $arg1, $arg2));
        }

        $element->click();
    }
}
