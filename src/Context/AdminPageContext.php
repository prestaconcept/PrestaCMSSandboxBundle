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
use Behat\Behat\Exception\PendingException;
use Behat\Mink\Exception\ExpectationException;
use Behat\Mink\Exception\ElementNotFoundException;

/**
 * Description of ThemeContext
 *
 * @author David Epely <depely@prestaconcept.net>
 */
class AdminPageContext extends BehatContext
{
    /**
     * @Then /^I should see the "([^"]*)" website selection and a link with selected locale "([^"]*)"$/
     */
    public function iShouldSeeTheWebsiteSelectionAndALinkWithSelectedLocale($arg1, $arg2)
    {
        $this->getMainContext()->assertElementContainsText("#website-selector", $arg1);
        $this->getMainContext()->assertElementOnPage("#website-selector div:contains($arg1) ul li a.locale_$arg2.active");
    }

    /**
     * @Given /^I should see a tree of pages$/
     */
    public function iShouldSeeATreeOfPages()
    {
        //tmp fake working page using sandbox demo
        $this->getMainContext()->visit("/admin/en/cms/page/website/sandbox/en");

        $this->getMainContext()->assertElementContainsText("#page-tree-container h4", "Pages");
        $this->getMainContext()->getSession()->wait(2, '(window.jQuery("#tree ul").length > 0)');
        $this->getMainContext()->assertNumElements(2, "#tree ul li");

        $js = <<<JS
                var assert = require("assert");

                try {
                    assert.equal(browser.text("h1"), "Pages");
                    assert.equal(browser.text("#page-tree-container h4"), "Navigation");
                } catch (err) {
                    stream.end(JSON.stringify(err.toString()));
                }

                stream.end();
JS;
        $out = $this->getMainContext()
                ->getSession('zombie')
                ->getDriver()
                ->getServer()
                ->evalJs($js);

        if (!empty($out)) {
            $e = json_decode($out);
            throw new ExpectationException($e, $this->getMainContext()->getSession('zombie'));
        }
    }

    /**
     * @Given /^I press "([^"]*)" block edit button$/
     */
    public function iPressBlockEditButton($arg1)
    {
        $session = $this->getMainContext()->getSession();
        $element = $session->getPage()->find(
            'css',
            "#cms-zone-websitesandboxpagehomecontent .page-zone-block:first-child a.action-edit"
        );

        if (!$element) {
            throw new ElementNotFoundException($session);
        }

        $element->click();
    }

    /**
     * @Then /^I should see a list of blocks$/
     */
    public function iShouldSeeAListOfBlocks()
    {
        throw new PendingException();
    }

    /**
     * @Then /^I should see a form with block configuration$/
     */
    public function iShouldSeeAFormWithBlockConfiguration()
    {
        throw new PendingException();
    }

    /**
     * @Then /^I should see the block highlighted$/
     */
    public function iShouldSeeTheBlockHighlighted()
    {
        throw new PendingException();
    }

    /**
     * @Then /^I should see a form for seo configuration$/
     */
    public function iShouldSeeAFormForSeoConfiguration()
    {
        throw new PendingException();
    }

    /**
     * @Then /^I should see a form for sub page creation$/
     */
    public function iShouldSeeAFormForSubPageCreation()
    {
        throw new PendingException();
    }

    /**
     * @Then /^I should see the sub-home page$/
     */
    public function iShouldSeeTheSubHomePage()
    {
        throw new PendingException();
    }
}
