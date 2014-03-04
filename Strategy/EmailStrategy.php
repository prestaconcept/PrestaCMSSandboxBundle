<?php
/**
 * This file is part of the PrestaCMSSandboxBundle
 *
 * (c) PrestaConcept <www.prestaconcept.net>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */
namespace Presta\CMSSandboxBundle\Strategy;

use Presta\CMSContactBundle\Model\Message;
use Presta\CMSContactBundle\Strategy\EmailStrategy as BaseEmailStrategy;

/**
 * @author Nicolas Bastien <nbastien@prestaconcept.net>
 */
class EmailStrategy extends BaseEmailStrategy
{
    /**
     * {@inheritdoc}
     */
    public function handle(Message $message)
    {
        //override to sent email to sender
        //this is just a demo !
        $this->setEmailTo($message->getContact()->getEmail());

        return parent::handle($message);
    }
}
