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

use Symfony\Component\HttpKernel\KernelInterface;
use Behat\Symfony2Extension\Context\KernelAwareInterface;

/**
 * @author Nicolas Bastien <nbastien@prestaconcept.net>
 */
class FeatureContext extends AdminContext implements KernelAwareInterface
{
    private $kernel;
    private $parameters;

    /**
     * Initializes context with parameters from behat.yml.
     *
     * @param array $parameters
     */
    public function __construct(array $parameters)
    {
        $this->parameters = $parameters;

        $this->useContext('theme', new AdminThemeContext);
        $this->useContext('website', new AdminWebsiteContext());
        $this->useContext('page', new AdminPageContext);
    }

    /**
     * Sets HttpKernel instance.
     * This method will be automatically called by Symfony2Extension ContextInitializer.
     *
     * @param KernelInterface $kernel
     */
    public function setKernel(KernelInterface $kernel)
    {
        $this->kernel = $kernel;
    }

    /**
     * @return Registry
     */
    protected function getDoctrine()
    {
        return $this->kernel->getContainer()->get('doctrine');
    }

    /**
     * @return DocumentManager
     */
    public function getDocumentManager()
    {
        return $this->kernel->getContainer()->get('doctrine_phpcr.odm.default_document_manager');
    }
}
