<?php
/**
 * This file is part of the PrestaCMS-Sandbox
 *
 * (c) PrestaConcept <www.prestaconcept.net>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */
namespace Application\Sonata\UserBundle\Document;

use Sonata\UserBundle\Document\BaseUser as BaseUser;

/**
 * @author Nicolas Bastien <nbastien@prestaconcept.net>
 */
class User extends BaseUser
{
    /**
     * @var integer $id
     */
    protected $id;

    /**
     * Get id
     *
     * @return integer $id
     */
    public function getId()
    {
        return $this->id;
    }
}
